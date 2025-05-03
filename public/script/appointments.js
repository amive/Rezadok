// عناصر النافذة المنبثقة
const modal = document.getElementById("confirmationModal");
const closeBtn = document.querySelector(".close-btn");
const modalCancelBtn = document.getElementById("modalCancelBtn");
const modalConfirmBtn = document.getElementById("modalConfirmBtn");
const modalMessage = document.getElementById("modalMessage");

const notificationModal = document.getElementById("notificationModal");
const okButton = document.getElementById("okButton");

const actionMessages = {
  confirm: "هل أنت متأكد من تأكيد هذا الموعد؟",
  cancel: "هل أنت متأكد من إلغاء هذا الموعد؟",
};

let currentAction = "";
let currentAppointmentId = 0;

// فتح نافذة التأكيد
function confirmAction(action, appointmentId) {
  currentAction = action;
  currentAppointmentId = appointmentId;
  modalMessage.textContent = actionMessages[action];
  modal.style.display = "block";
}

// إغلاق نافذة التأكيد
closeBtn.onclick = modalCancelBtn.onclick = function () {
  modal.style.display = "none";
};

// عند الضغط على "تأكيد"
modalConfirmBtn.onclick = function () {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = "";

  const actionInput = document.createElement("input");
  actionInput.type = "hidden";
  actionInput.name = "action";
  actionInput.value = currentAction;

  const idInput = document.createElement("input");
  idInput.type = "hidden";
  idInput.name = "appointment_id";
  idInput.value = currentAppointmentId;

  form.appendChild(actionInput);
  form.appendChild(idInput);
  document.body.appendChild(form);
  form.submit();
};

// نافذة تنبيه العد التنازلي (موعد خلال ساعة)
okButton.onclick = function () {
  notificationModal.style.display = "none";
};
document.addEventListener("DOMContentLoaded", () => {
  // دالة لحساب الفارق الزمني بين الآن وموعد الموعد
  function checkCountdowns() {
    const countdowns = document.querySelectorAll(".countdown");
    const now = new Date();

    countdowns.forEach((span) => {
      const appointmentTime = new Date(span.getAttribute("data-datetime"));
      const diffMs = appointmentTime - now;
      const diffMinutes = Math.floor(diffMs / 1000 / 60);

      if (diffMinutes <= 60 && diffMinutes > 0) {
        // عرض الوقت المتبقي
        const hours = Math.floor(diffMinutes / 60);
        const minutes = diffMinutes % 60;
        span.textContent = `${
          hours > 0 ? hours + " ساعة و " : ""
        }${minutes} دقيقة`;

        // عرض النافذة المنبثقة مرة واحدة فقط
        if (!sessionStorage.getItem("alertShown")) {
          document.getElementById("notificationModal").style.display = "block";
          sessionStorage.setItem("alertShown", true);
        }
      } else if (diffMinutes <= 0) {
        span.textContent = "الآن";
      } else {
        const hours = Math.floor(diffMinutes / 60);
        const minutes = diffMinutes % 60;
        span.textContent = `${
          hours > 0 ? hours + " ساعة و " : ""
        }${minutes} دقيقة`;
      }
    });
  }

  // تحقق من العد التنازلي كل 30 ثانية
  setInterval(checkCountdowns, 30000);
  checkCountdowns();

  // إغلاق النافذة المنبثقة
  document.getElementById("okButton").addEventListener("click", () => {
    document.getElementById("notificationModal").style.display = "none";
  });

  // نافذة التأكيد
  const modal = document.getElementById("confirmationModal");
  const closeBtn = document.querySelector(".close-btn");
  const cancelBtn = document.getElementById("modalCancelBtn");

  closeBtn.onclick = () => (modal.style.display = "none");
  cancelBtn.onclick = () => (modal.style.display = "none");

  // تنفيد العملية عند التأكيد
  let formAction = "";
  let formId = "";

  window.confirmAction = function (action, appointmentId) {
    formAction = action;
    formId = appointmentId;
    document.getElementById("modalMessage").textContent =
      action === "confirm"
        ? "هل تريد تأكيد هذا الموعد؟"
        : "هل تريد إلغاء هذا الموعد؟";
    modal.style.display = "block";
  };

  document.getElementById("modalConfirmBtn").onclick = () => {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "";

    const inputAction = document.createElement("input");
    inputAction.type = "hidden";
    inputAction.name = "action";
    inputAction.value = formAction;

    const inputId = document.createElement("input");
    inputId.type = "hidden";
    inputId.name = "appointment_id";
    inputId.value = formId;

    form.appendChild(inputAction);
    form.appendChild(inputId);

    document.body.appendChild(form);
    form.submit();
  };
});
