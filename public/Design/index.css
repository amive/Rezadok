/* ===== Modern CSS Reset with RTL Support ===== */
:root {
  --primary-color: #4285f4; /* Google blue */
  --secondary-color: #34a853; /* Google green */
  --accent-color: #ea4335; /* Google red */
  --text-primary: #202124;
  --text-secondary: #5f6368;
  --bg-primary: #ffffff;
  --bg-secondary: #f8f9fa;
  --border-radius: 8px;
  --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html[lang="ar"] {
  direction: rtl;
}

body {
  font-family: 'Tahoma', 'Segoe UI', sans-serif;
  line-height: 1.6;
  color: var(--text-primary);
  background-color: var(--bg-primary);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* ===== Header Styles ===== */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: var(--bg-primary);
  box-shadow: var(--box-shadow);
  position: sticky;
  top: 0;
  z-index: 100;
}

header h2 {
  font-size: 1.8rem;
  font-weight: 600;
  color: var(--primary-color);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

header nav {
  display: flex;
  gap: 1.5rem;
}

.icon-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: var(--text-secondary);
  font-size: 0.9rem;
  transition: var(--transition);
}

.icon-btn i {
  font-size: 1.2rem;
  margin-bottom: 0.3rem;
}

.icon-btn:hover {
  color: var(--primary-color);
  transform: translateY(-2px);
}

/* ===== Main Content Styles ===== */
.container-index {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 2rem;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
}

.container-index h2 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  color: var(--text-primary);
}

.container-index h2 span {
  color: var(--primary-color);
}

#p_index {
  font-size: 1.2rem;
  color: var(--text-secondary);
  max-width: 600px;
  margin-bottom: 2rem;
  line-height: 1.8;
}

/* ===== Toggle Buttons ===== */
.toggle-buttons {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.toggle-buttons button {
  padding: 0.8rem 1.8rem;
  border: none;
  border-radius: var(--border-radius);
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition);
  background-color: var(--bg-secondary);
  color: var(--text-primary);
}

.toggle-buttons button:hover {
  background-color: #e0e0e0;
}

.toggle-buttons button:focus {
  outline: 2px solid var(--primary-color);
  outline-offset: 2px;
}

/* ===== Form Containers ===== */
.container {
  width: 100%;
  max-width: 500px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: var(--bg-primary);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  display: none; /* Hidden by default */
}

.container.visible {
  display: block;
  animation: fadeIn 0.5s ease-out;
}

.container h3 {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: var(--text-primary);
  display: flex;
  align-items: center;
  gap: 0.5rem;
  justify-content: center;
}

/* ===== Form Elements ===== */
form {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

input,
select,
textarea,
button {
  padding: 0.8rem 1rem;
  border: 1px solid #dadce0;
  border-radius: var(--border-radius);
  font-size: 1rem;
  transition: var(--transition);
  width: 100%;
}

input:focus,
select:focus,
textarea:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2);
  outline: none;
}

select {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1rem;
}

html[lang="ar"] select {
  background-position: left 1rem center;
}

textarea {
  min-height: 100px;
  resize: vertical;
}

/* ===== Button Styles ===== */
.btn-login-register,
#btn-login-register {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 0.9rem;
  font-size: 1rem;
  font-weight: 500;
  border-radius: var(--border-radius);
  cursor: pointer;
  transition: var(--transition);
  margin-top: 0.5rem;
}

.btn-login-register:hover,
#btn-login-register:hover {
  background-color: #3367d6;
  box-shadow: 0 2px 5px rgba(66, 133, 244, 0.3);
}

/* ===== Doctor Fields ===== */
#doctorFields {
  display: none;
  flex-direction: column;
  gap: 1.2rem;
  padding: 1rem;
  background-color: var(--bg-secondary);
  border-radius: var(--border-radius);
  margin-top: 0.5rem;
}

#doctorFields.visible {
  display: flex;
}

/* ===== Hidden Utility Class ===== */
.hidden {
  display: none !important;
}

/* ===== Animations ===== */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ===== Responsive Design ===== */
@media (max-width: 768px) {
  header {
    flex-direction: column;
    padding: 1rem;
  }
  
  header nav {
    margin-top: 1rem;
    flex-wrap: wrap;
    justify-content: center;
  }
  
  .container-index h2 {
    font-size: 2rem;
  }
  
  .container {
    padding: 1.5rem;
  }
}

@media (max-width: 480px) {
  .toggle-buttons {
    flex-direction: column;
    width: 100%;
  }
  
  .toggle-buttons button {
    width: 100%;
  }
}