<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name')}} | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
      body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }
      .login-container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        width: 500px;
        text-align: center;
      }
      .login-container h2 {
        margin-bottom: 20px;
        color: #333;
      }
      .form-group {
        margin-bottom: 15px;
        text-align: left;
      }
      .form-group label {
        display: block;
        font-size: 14px;
        margin-bottom: 5px;
        color: #555;
      }
      .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
      }
      .login-btn {
        width: 100%;
        padding: 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }
      .login-btn:hover {
        background: #0056b3;
      }
      .extra-links {
        margin-top: 15px;
        font-size: 14px;
      }
      .extra-links a {
        color: #007bff;
        text-decoration: none;
      }
      .extra-links a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <div class="login-container">
      <h2>Login</h2>
      @if(session('error'))
        <div class="alert alert-danger">
          <strong>Warning! </strong>{{ session('error') }}<br>
        </div>
      @endif
      <form method="post" action="{{route('sign_in')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="login-btn">Login</button>
      </form>
    </div>
  </body>
</html>
