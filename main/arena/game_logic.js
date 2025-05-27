//timer, user/machine logic 

let timer;
let userAnswered = false;
let machineAnswered = false;
let correctOptionIndex = -1;

// ⏬ Load question from PHP
async function loadQuestion() {
  const response = await fetch('get_question.php?tipo=A&nivel=N001');
  const data = await response.json();

  displayQuestion(data);
  startTimer();
  simulateMachineAnswer(data.options);
}

function displayQuestion(data) {
  const cloud = document.querySelector('.cloud');
  cloud.src = data.image;

  const container = document.querySelector('.preguntas');
  // Remove old options if any
  document.querySelectorAll('.option').forEach(el => el.remove());

  data.options.forEach((option, index) => {
    const img = document.createElement('img');
    img.src = option.img;
    img.className = 'option';
    img.style.width = '150px';
    img.style.margin = '10px';
    img.dataset.correct = option.correct;
    img.dataset.index = index;

    img.addEventListener('click', () => handleUserAnswer(img));
    container.appendChild(img);

    if (option.correct) correctOptionIndex = index;
  });
}

function handleUserAnswer(img) {
  if (userAnswered) return;
  userAnswered = true;

  const isCorrect = img.dataset.correct === 'true';
  img.style.border = isCorrect ? '4px solid green' : '4px solid red';

  clearInterval(timer);
  showResult('user', isCorrect);
}

function simulateMachineAnswer(options) {
  const randomTime = Math.floor(Math.random() * 15 + 3) * 1000; // Between 3–18s

  setTimeout(() => {
    if (machineAnswered) return;
    machineAnswered = true;

    const randomIndex = Math.floor(Math.random() * options.length);
    const option = document.querySelectorAll('.option')[randomIndex];
    option.style.border = randomIndex == correctOptionIndex ? '4px dashed green' : '4px dashed red';

    if (!userAnswered) clearInterval(timer);
    showResult('machine', randomIndex == correctOptionIndex);
  }, randomTime);
}

function startTimer() {
  let seconds = 20;
  timer = setInterval(() => {
    console.log(`⏱️ ${seconds} seconds remaining`);
    seconds--;

    if (seconds < 0) {
      clearInterval(timer);
      if (!userAnswered) showResult('user', false);
      if (!machineAnswered) showResult('machine', false);
    }
  }, 1000);
}

function showResult(player, correct) {
  console.log(`${player.toUpperCase()} ${correct ? '✔️ correct' : '❌ incorrect'}`);
  // You can add points, animations, etc. here
}