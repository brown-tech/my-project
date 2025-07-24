function getWeather() {
  const city = document.getElementById('cityInput').value;
  const apiKey = 'a9b2d1c8abf88c9f26fa77d6cb412d3e'; // 🔑 Replace with your OpenWeatherMap API key
  const url = 'https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric';

  fetch(url)
    .then(response => response.json())
    .then(data => {
      if (data.cod === '404') {
        document.getElementById('weatherResult').innerHTML = 'City not found 😢';
        return;
      }

      const temp = data.main.temp;
      const description = data.weather[0].description;
      const humidity = data.main.humidity;
      const icon = data.weather[0].icon;

      document.getElementById('weatherResult').innerHTML = `
        <h2>${data.name}, ${data.sys.country}</h2>
        <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="icon">
        <p><strong>${description}</strong></p>
        <p>🌡️ Temperature: ${temp} °C</p>
        <p>💧 Humidity: ${humidity}%</p>
      `;
    })
    .catch(error => {
      console.error(error);
      document.getElementById('weatherResult').innerHTML = 'An error occurred.';
    });
}
