let display = document.getElementById("display");

function append(char) {
  if (display.innerText === "0" || display.innerText === "Error") {
    display.innerText = char;
  } else {
    display.innerText += char;
  }
}

function clearDisplay() {
  display.innerText = "0";
}

function backspace() {
  let current = display.innerText;
  display.innerText = current.length > 1 ? current.slice(0, -1) : "0";
}

function calculate() {
  try {
    let result = eval(display.innerText.replace(/×/g, '*').replace(/÷/g, '/'));
    display.innerText = result;
  } catch (e) {
    alert("Invalid Expression");
    display.innerText = "Error";
  }
}

// Keyboard support
document.addEventListener("keydown", (e) => {
  const validKeys = "0123456789+-*/().%";
  if (validKeys.includes(e.key)) {
    append(e.key);
  } else if (e.key === "Enter") {
    e.preventDefault();
    calculate();
  } else if (e.key === "Backspace") {
    e.preventDefault();
    backspace();
  } else if (e.key === "Escape") {
    clearDisplay();
  }
});
