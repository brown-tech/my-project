## 📘 Blood Demand Prediction System

This project predicts the number of units needed next month for each blood type at a blood bank, using a Decision Tree regression model based on past request data.

-------------------------------------------------------------------------------------------------------------------------------------------------------------------

## 📂 Project Files

* **sample\_data\_all\_types.csv**
  Contains monthly blood request counts for all 8 blood types (A+, A-, B+, B-, AB+, AB-, O+, O-) from January 2024 to June 2025.

* **predict\_blood\_demand.py**
  Python script that:

  * Loads the sample data CSV.
  * Trains a Decision Tree model for each blood type.
  * Predicts the number of units needed for the next month.
  * Prints the predictions and average model error (MAE) for each blood type.

-------------------------------------------------------------------------------------------------------------------------------------------------------------------

## 🚀 How to set it up and run

1️⃣ **Install Python (if not already installed):**

* Download the installer at [python.org/downloads/windows](https://www.python.org/downloads/windows/).
* During installation, check the box **“Add Python to PATH”**.

2️⃣ **Open PowerShell or Command Prompt.**

3️⃣ **Navigate to the folder with your files**, for example:

```powershell
cd "C:\Users\francis\Desktop\blood prediction demand"
```

4️⃣ **Install required Python libraries:**

```powershell
pip install pandas scikit-learn
```

or if `pip` doesn’t work:

```powershell
python -m pip install pandas scikit-learn
```

5️⃣ **Run the prediction script:**

```powershell
python predict_blood_demand.py
```

------------------------------------------------------------------------------------------------------------------------------------------------------------------

## ✅ What to expect

After running the script, you’ll see predictions like:

```
=== Blood Demand Predictions for Next Month ===

[A+] Predicted next month requests: 190 units | MAE: 7.5 units
[A-] Predicted next month requests: 70 units | MAE: 3.0 units
...
```

Where:

* **Predicted next month requests** shows how many units of each blood type you’ll likely need.
* **MAE (Mean Absolute Error)** shows the average error between past predictions and actual values (lower is better).

---

## 📊 Interpreting the output

* If MAE is small relative to your typical monthly requests, your model predictions are reliable.
* If MAE is high, you may need more data or better features (e.g., incorporating holidays, local events, or population changes).

---

## 🔧 Customizing for your real data

* Replace `sample_data_all_types.csv` with your own historical request data.

* Ensure your CSV has these columns:

  * `Month` (YYYY-MM format),
  * `Blood_Type` (e.g., A+, O-),
  * `Number_Requested` (integer).

* Keep the same column names for the script to work without modification.
