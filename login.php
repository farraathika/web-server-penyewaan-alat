<?php
include 'db/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id_admin FROM admin WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['login_user'] = $email;
        header('Location: dashboard.php');
    } else {
        $error = "Invalid email or password";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-size: cover;
            background-position: center;
            background-image: url("assets/backgorund.jpg");

        }
    </style>
</head>

<body class="w-full h-screen flex flex-row justify-center items-center">
    <div
        class="relative flex w-96 flex-col rounded-xl bg-black bg-opacity-30 backdrop-blur-md bg-clip-border text-gray-700 shadow-md">
        <h3
            class="block text-center py-3 font-sans text-3xl font-semibold leading-snug tracking-normal text-white antialiased">
            Sign In
        </h3>
        <form method="post" action="">
            <div class="flex flex-col gap-4 p-6">
                <div class="relative h-11 w-full min-w-[200px]">
                    <input type="email" id="email" name="email" required placeholder=""
                        class="peer h-full w-full rounded-md border border-gray-400 border-t-transparent bg-transparent px-3 py-3 font-sans text-sm font-normal text-white outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-white placeholder-shown:border-t-white focus:border-2 focus:border-white focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50 bg-opacity-0" />
                    <label
                        class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-white transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-gray-400 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-gray-400 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[4.1] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-cyan-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-white peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-white peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
                        Email
                    </label>
                </div>

                <div class="relative h-11 w-full min-w-[200px] mt-4">
                    <input type="password" id="password" name="password" required placeholder=""
                        class="peer h-full w-full rounded-md border border-gray-400 border-t-transparent bg-transparent px-3 py-3 font-sans text-sm font-normal text-white outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-white placeholder-shown:border-t-white focus:border-2 focus:border-white focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-blue-gray-50 bg-opacity-0" />
                    <label
                        class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none text-[11px] font-normal leading-tight text-white transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-gray-400 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-gray-400 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[4.1] peer-placeholder-shown:text-blue-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-cyan-500 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-white peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:border-white peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-blue-gray-500">
                        Password
                    </label>
                </div>
                <div class="-ml-2.5">
                    <div class="inline-flex items-center">
                        <label data-ripple-dark="true" for="checkbox"
                            class="relative flex cursor-pointer items-center rounded-full p-3">
                            <input id="checkbox"
                                class="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-cyan-500 checked:bg-cyan-500 checked:before:bg-cyan-500 hover:before:opacity-10"
                                type="checkbox" />
                            <span
                                class="pointer-events-none absolute top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                                <svg stroke-width="1" stroke="currentColor" fill="currentColor" viewBox="0 0 20 20"
                                    class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        fill-rule="evenodd"></path>
                                </svg>
                            </span>
                        </label>
                        <label for="checkbox" class="mt-px cursor-pointer select-none font-light text-white">
                            Remember Me
                        </label>
                    </div>
                </div>
            </div>


            <div class="p-6 pt-0">
                <button data-ripple-light="true" type="submit"
                    class="block w-full select-none rounded-lg bg-white shadow-md py-3 px-6 text-center align-middle font-sans text-xs font-bold uppercase text-gray-800 hover:bg-gray-300 transition-all hover:shadow-lg active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                    Sign In
                </button>
                <p
                    class="mt-6 flex justify-center font-sans text-sm text-white font-light leading-normal text-inherit antialiased">
                    Don't have an account?
                    <a class="ml-1 block font-sans text-sm font-bold leading-normal text-cyan-500 antialiased"
                        href="register.php">
                        Sign up
                    </a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>
