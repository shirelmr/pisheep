<!-- FIXED game.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VS Computer - Game Arena</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url("fondo.png");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
      font-family: Arial, sans-serif;
    }
    
    .map-container {
      position: relative;
      width: 100vw;
      height: 100vh;
      min-width: 1100px;
      background-color: #b5d6d6;
    }
    
    /* Question in cloud - adjust position as needed */
    .cloud {
      position: absolute;
      top: 20%;
      left: 50%;
      transform: translateX(-50%);
      max-width: 300px;
      border-radius: 10px;
      z-index: 100;
    }
    
    /* Answer options positioned over the circles */
    .answer_1 {
      position: absolute;
      top: 60%;
      left: 35%;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.2s ease;
      z-index: 100;
      object-fit: cover;
    }
    
    .answer_2 {
      position: absolute;
      top: 60%;
      right: 35%;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.2s ease;
      z-index: 100;
      object-fit: cover;
    }
    
    .answer_1:hover, .answer_2:hover {
      transform: scale(1.1);
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    
    /* Computer thinking indicator */
    .computer-thinking {
      position: absolute;
      top: 80px;
      right: 20px;
      background: rgba(244, 67, 54, 0.9);
      color: white;
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 14px;
      display: none;
      animation: pulse 1s infinite;
      z-index: 1000;
    }
    
    @keyframes pulse {
      0% { opacity: 0.7; }
      50% { opacity: 1; }
      100% { opacity: 0.7; }
    }
    
    /* Result messages */
    .result-message {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0,0,0,0.8);
      color: white;
      padding: 20px 40px;
      border-radius: 15px;
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      display: none;
      z-index: 2000;
    }
    
    .win-message {
      background: rgba(76, 175, 80, 0.9) !important;
    }
    
    .lose-message {
      background: rgba(244, 67, 54, 0.9) !important;
    }
  </style>
</head>
<body>
  <img src="../arena/arena_bg.svg" class="map-container">
  
  <!-- VS Computer UI -->
  <div class="computer-thinking" id="computer-thinking">
    🤖 Computadora pensando...
  </div>
  
  <div class="result-message" id="result-message"></div>

  <?php
  $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'A';
  include 'get_question.php';
  ?>

  <script>
    let questionNumber = <?php echo ($tipo === 'A') ? 1 : 2; ?>;
    
    // Get current score from URL parameter or initialize
    function getCurrentScore() {
      const urlParams = new URLSearchParams(window.location.search);
      return parseInt(urlParams.get('score')) || 0;
    }
    
    function handleAnswer(img) {
      console.log('Answer clicked!', img); // Debug log
      
      const correct = img.getAttribute('data-correcta') === "1";
      const computerThinking = document.getElementById('computer-thinking');
      const resultMessage = document.getElementById('result-message');
      
      console.log('Is correct?', correct); // Debug log
      
      // Get current score and update if correct
      let currentScore = getCurrentScore();
      if (correct) {
        currentScore++;
      }
      
      console.log('Current score:', currentScore, 'Question number:', questionNumber); // Debug log
      
      // Disable all answers
      document.querySelectorAll('img[data-correcta]').forEach(el => {
        el.style.pointerEvents = 'none';
      });
      
      // Show computer thinking
      computerThinking.style.display = 'block';
      
      // Visual feedback for user choice
      img.style.border = correct ? "4px solid green" : "4px solid red";
      
      setTimeout(() => {
        computerThinking.style.display = 'none';
        
        if (correct) {
          // User wins this round
          resultMessage.textContent = "¡Ganaste esta ronda! 🎉";
          resultMessage.className = "result-message win-message";
        } else {
          // Computer wins this round
          resultMessage.textContent = "La computadora ganó esta ronda 🤖";
          resultMessage.className = "result-message lose-message";
        }
        
        resultMessage.style.display = 'block';
        
        setTimeout(() => {
          resultMessage.style.display = 'none';
          
          // Check if game is over (2 questions)
          if (questionNumber >= 2) {
            // Game over - check final score
            setTimeout(() => {
              if (currentScore === 2) {
                // User got both questions correct
                window.location.href = 'win.html';
              } else {
                // User got 0 or 1 questions correct
                window.location.href = 'lose.html';
              }
            }, 1000);
            
          } else {
            // Continue to next question with updated score
            setTimeout(() => {
              window.location.href = "game.php?tipo=B&score=" + currentScore;
            }, 500);
          }
        }, 2000);
        
      }, Math.random() * 2000 + 1000); // Computer "thinks" for 1-3 seconds
    }
    
    // Debug: Check if answers loaded
    console.log('Found answers:', document.querySelectorAll('img[data-correcta]').length);
    console.log('Starting score:', getCurrentScore());
  </script>
</body>
</html>