<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rezadok login</title>
  </head>
  <body>
    <div class="register visible" id="register">
      <h2 style="font-size: 16px">Create a new account</h2>

      <form id="register-form" method="post">
        <input type="text" name="name" placeholder="Name" required />

        <input type="email" name="email" placeholder="Email" required />

        <input
          type="text"
          name="phoneNumber"
          placeholder="Phone Number"
          required
        />
        <input
          type="password"
          name="password"
          placeholder="Password"
          required
        />

        <button type="submit">Register</button>

        <p>
          <a class="switch" onclick="toggleForms()"
            >Already have an account? Log in</a
          >
        </p>
      </form>
    </div>

    <div class="login hidden" id="login">
      <h2 style="font-size: 36px">Login</h2>

      <form id="login-form" method="post">
        <input type="email" name="email" placeholder="Email" required />

        <input
          type="password"
          name="password"
          placeholder="Password"
          required
        />

        <button type="submit">Login</button>

        <p>
          <a class="switch" onclick="toggleForms()"
            >Don't have an account? Register</a
          >
        </p>
      </form>
    </div>
  </body>
</html>
