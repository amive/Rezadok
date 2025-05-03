document.addEventListener("DOMContentLoaded", function () {
  const chatBox = document.getElementById("chat-box");
  let enlargedImageSrc = null; // Track the currently enlarged image

  // Function to add the click event listener for enlarging images
  function addImageClickListener() {
    chatBox.querySelectorAll("img").forEach((img) => {
      img.addEventListener("click", function () {
        if (img.classList.contains("enlarged")) {
          img.classList.remove("enlarged");
          enlargedImageSrc = null; // Clear the enlarged state
        } else {
          // Remove the 'enlarged' class from any previously enlarged image
          chatBox.querySelectorAll(".enlarged").forEach((enlargedImg) => {
            enlargedImg.classList.remove("enlarged");
          });

          img.classList.add("enlarged");
          enlargedImageSrc = img.src; // Save the enlarged image's source
        }
      });
    });
  }

  // Scroll to the bottom of the chat box on page load
  chatBox.scrollTop = chatBox.scrollHeight;

  // Update chat messages every 3 seconds
  setInterval(function () {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_messages.php?receiver_id=" + receiverId, true);
    xhr.onload = function () {
      if (xhr.status == 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.messages.length > 0) {
          chatBox.innerHTML = ""; // Clear previous messages
          response.messages.forEach(function (msg) {
            var messageDiv = document.createElement("div");
            messageDiv.classList.add(
              msg.sender_id == userId ? "sent" : "received"
            );
            messageDiv.innerHTML = `<p>${msg.message}</p><small>${msg.created_at}</small>`;

            // Add image or file attachment
            if (msg.file_path) {
              if (msg.file_type == "image") {
                const imgElement = document.createElement("img");
                imgElement.src = msg.file_path;
                imgElement.alt = "مرفق";
                imgElement.style.maxWidth = "200px";

                // Reapply the 'enlarged' class if this image was enlarged
                if (msg.file_path === enlargedImageSrc) {
                  imgElement.classList.add("enlarged");
                }

                messageDiv.appendChild(imgElement);
              } else {
                messageDiv.innerHTML += `<a href="${msg.file_path}" target="_blank">📎 تحميل الملف</a>`;
              }
            }

            chatBox.appendChild(messageDiv);
          });

          // Reapply the image click listener after updating the chat box
          addImageClickListener();

          // Scroll to the bottom of the chat box
          chatBox.scrollTop = chatBox.scrollHeight;
        }
      }
    };
    xhr.send();
  }, 3000); // Update every 3 seconds
});
