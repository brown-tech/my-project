import pandas as pd
from sklearn.tree import DecisionTreeRegressor
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error
import mysql.connector

# ✅ Connect to MySQL FIRST (before reading data)
conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='bloodbank'
)

# ✅ Define the SQL query BEFORE using it
query = "SELECT month, blood_type, number_requested FROM blood_requests_history"

# ✅ Load data directly from database
data = pd.read_sql(query, conn)

# Prepare the dataframe columns
data['Month'] = pd.to_datetime(data['month'])
data['Blood_Type'] = data['blood_type']
data['Number_Requested'] = data['number_requested']

# Optional cleanup: keep only needed columns
data = data[['Month', 'Blood_Type', 'Number_Requested']]

# List unique blood types
blood_types = data['Blood_Type'].unique()

print("=== Blood Demand Predictions for Next Month ===\n")

# Reuse the existing connection for updating predictions
cursor = conn.cursor()

for bt in blood_types:
    # Prepare data for one blood type
    df_bt = data[data['Blood_Type'] == bt].sort_values('Month').reset_index(drop=True)
    df_bt['Requests_this_month'] = df_bt['Number_Requested']
    df_bt['Requests_last_month'] = df_bt['Requests_this_month'].shift(1)
    df_bt['Requests_2_months_ago'] = df_bt['Requests_this_month'].shift(2)
    df_bt['Target_next_month'] = df_bt['Requests_this_month'].shift(-1)
    df_bt.dropna(inplace=True)
    
    if len(df_bt) < 5:
        print(f"[{bt}] Skipped: Not enough data points (only {len(df_bt)} rows).")
        continue
    
    features = ['Requests_this_month', 'Requests_last_month', 'Requests_2_months_ago']
    X_bt = df_bt[features]
    y_bt = df_bt['Target_next_month']
    
    X_train_bt, X_test_bt, y_train_bt, y_test_bt = train_test_split(
        X_bt, y_bt, test_size=0.2, shuffle=False
    )
    
    model_bt = DecisionTreeRegressor(max_depth=5, random_state=42)
    model_bt.fit(X_train_bt, y_train_bt)
    
    # Evaluate
    y_pred_bt = model_bt.predict(X_test_bt)
    mae = mean_absolute_error(y_test_bt, y_pred_bt)
    
    # Predict next month
    latest_bt = X_bt.iloc[-1:]
    pred_bt = model_bt.predict(latest_bt)
    
    print(f"[{bt}] Predicted next month requests: {pred_bt[0]:.0f} units | MAE: {mae:.1f} units")
    
    # Update prediction in MySQL
    cursor.execute("""
        REPLACE INTO blood_predictions (blood_type, predicted_units, updated_at)
        VALUES (%s, %s, NOW())
    """, (bt, int(pred_bt[0])))

# Commit and close connection
conn.commit()
conn.close()

print("\nPredictions saved to database. Done!")
