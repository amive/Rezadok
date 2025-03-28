<!DOCTYPE html>
<html lang="ar">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>مراسلة الطبيب</title>
    <style>
      header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #2c3e50;
        color: white;
        z-index: 1000;
      }
      nav a {
        color: white;
        text-decoration: none;
        font-size: 18px;
      }
      body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        text-align: center;
        overflow: hidden;
      }
      .chat-container {
        width: 80%;
        max-width: 500px;
        margin: 50px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: left;
        scale: 0.6;
        transform: translateY(-25%);
        font-size: 20px;
      }
      .chat-box {
        height: 300px;
        overflow-y: auto;
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        background: #fff;
      }
      .message {
        padding: 8px;
        border-radius: 5px;
      }
      .sent {
        background: #007bff;
        display: block;
        clear: both;
        margin-bottom: 2px;
        max-width: 50%;
        white-space: normal;
        word-wrap: break-word;
        width: fit-content;
        float: right;
        text-align: end;
        color: white;
      }
      .received {
        width: 50%;
        background: #e9ecef;
        display: block;
        clear: both;
        margin-bottom: 2px;
        text-align: end;
        max-width: 50%;
        white-space: normal;
        word-wrap: break-word;
        width: max-content;
      }
      input,
      button,
      select {
        width: 70%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 5px;
        transform: translateX(2%);
        border: 1px solid #ccc;
      }
      button {
        background-color: #007bff;
        width: 15%;
        border-radius: 0%;
        border-top-left-radius: 25%;
        font-size: 17px;
        transform: translateX(55%);
        color: white;
        cursor: pointer;
      }
    </style>
  </head>
  <header>
    <h2><i class="fa-solid fa-stethoscope"></i> Rezadok</h2>
    <nav>
      <a href="index.php" class="icon-btn" data-text="الرئيسية"
        ><i class="fa-solid fa-house"></i
      ></a>
      <a href="#" class="icon-btn" data-text="الخدمات"
        ><i class="fa-solid fa-user-doctor"></i
      ></a>
      <a href="#" class="icon-btn" data-text="من نحن"
        ><i class="fa-solid fa-circle-info"></i
      ></a>
      <a href="#" class="icon-btn" data-text="اتصل بنا"
        ><i class="fa-solid fa-phone"></i>
      </a>
    </nav>
  </header>
  <body>
    <div class="chat-container">
      <h2>مراسلة الطبيب</h2>
      <label for="doctor">اختر الطبيب:</label>
      <select id="doctor">
        <option value="doctor1">د. نيغا بلال</option>
        <option value="doctor2">د. امين قريريفة</option>
      </select>

      <div class="chat-box" id="chatBox">
        <div class="message received">
          مرحبًا، كيف يمكنني مساعدتك ايها المريض اللعين؟
        </div>
      </div>

      <input type="text" id="messageInput" placeholder="اكتب رسالتك هنا..." />
      <button onclick="sendMessage()">إرسال</button>

      <input type="file" id="fileInput" accept="image/*, .pdf, .doc, .docx" />
      <button onclick="sendFile()">إرسال الملف</button>
    </div>

    <script>
      document
        .getElementById("messageInput")
        .addEventListener("keydown", function (event) {
          if (event.key === "Enter") {
            event.preventDefault;
            sendMessage();
          }
        });
      function sendMessage() {
        let messageInput = document.getElementById("messageInput");
        let chatBox = document.getElementById("chatBox");
        let messageText = messageInput.value.trim();

        if (messageText !== "") {
          let messageDiv = document.createElement("div");
          messageDiv.classList.add("message", "sent");
          messageDiv.innerText = messageText;
          chatBox.appendChild(messageDiv);
          chatBox.scrollTop = chatBox.scrollHeight;
          messageInput.value = "";
        }
      }
      function sendFile() {
        let fileInput = document.getElementById("fileInput");
        let chatBox = document.getElementById("chatBox");

        if (fileInput.files.length === 0) {
          alert("يرجى اختيار ملف أولاً!");
          return;
        }

        let file = fileInput.files[0];
        let messageDiv = document.createElement("div");
        messageDiv.classList.add("message", "sent");

        if (file.type.startsWith("image/")) {
          let img = document.createElement("img");
          img.src = URL.createObjectURL(file);
          img.style.maxWidth = "200px";
          messageDiv.style.background = "none";
          img.style.borderRadius = "10px";
          messageDiv.appendChild(img);
        } else {
          let fileLink = document.createElement("a");
          fileLink.href = URL.createObjectURL(file);
          fileLink.style.color = "white";
          fileLink.textContent = file.name;
          fileLink.target = "_blank";
          messageDiv.appendChild(fileLink);
        }

        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
        fileInput.value = "";
      }
    </script>
  </body>
</html>
