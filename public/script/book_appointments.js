// تحديد أقل تاريخ ووقت مسموح به (الآن)
const now = new Date();
now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // لتفادي فرق التوقيت
document.getElementById("appointment_date").min = now
  .toISOString()
  .slice(0, 16);
// إضافة كود لتأكيد الحجز
document.querySelector("form").addEventListener("submit", function (event) {
  if (!confirm("هل أنت متأكد من تأكيد الحجز؟")) {
    event.preventDefault();
  }
});
