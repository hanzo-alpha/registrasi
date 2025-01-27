<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title> SoonX - Tailwind CSS Coming Soon HTML Page Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta
        content="SoonX - Coming Soon Tailwind CSS 3 HTML Template is a multi purpose landing page template built for any Count Down Web Page, Coming Soon Page, Launching Web, Launching Product Website, agency or business Startup. It’s fully responsive and built Tailwind v3"
        name="description"/>
    <meta content="Getappui" name="author"/>

    <!-- favicon -->
    <link rel="shortcut icon" href="images/favicon.ico">

    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@200;300;400;500;600;700&display=swap"
          rel="stylesheet"/>

    <!-- Tailwind css Cdn -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="tailwind.config.js"></script>

</head>

<body class="font-body">

<section class="lg:h-screen flex items-center justify-center lg:py-32 py-20 relative">
    <div class="overflow-hidden">
        <img class="absolute inset-0 h-full w-full object-cover bg-cover bg-bottom" src="images/img-11.jpg"
             alt="build your website image">
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    <div class="container">
        <div class="w-full h-full relative inline-block bg-neutral-900/5 backdrop-blur-2xl">
            <div class="grid lg:grid-cols-2 items-center">
                <div class="relative">
                    <img src="images/img-11.jpg" alt="" class="w-full h-full">
                    <div class="absolute inset-0 bg-black/60"></div>
                </div>

                <div class="text-center text-white">
                    <div class="p-5">
                        <div class="max-w-lg mx-auto">
                            <h3 class="text-3xl font-semibold">Our website is Coming Soon</h3>
                            <p class="text-white/70 mt-10">Lorem ipsum dolor sit amet adipicing elit. Mauris porttior
                                id, lobortis tempor. Donec sapien diam aliquet in uitricies.</p>
                        </div>

                        <div class="mt-10">
                            <div class="backdrop-blur-2xl bg-white/20 rounded-md max-w-lg mx-auto">
                                <form class="w-full flex items-center justify-center mt-7">
                                    <input type="email" name="email" id="email"
                                           class="w-full p-4 border-0 focus:outline-none focus:ring-0 text-sm text-white placeholder:text-white bg-transparent"
                                           placeholder="Enter Your Email" autocomplete="off">
                                    <button
                                        class="py-2 px-6 me-2 border-0 text-white font-semibold text-sm rounded-md backdrop-blur-2xl bg-blue-500 hover:bg-blue-600 hover:text-white transition-all duration-500">
                                        <div class="flex items-center justify-center gap-1">
                                            <span>Submit</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="flex items-center shadow-lg rounded-full max-w-xs mx-auto mt-10">
                            <button
                                class="py-3 w-full rounded-s-md border border-white/60 text-white font-semibold text-sm hover:bg-blue-600 hover:border-blue-600 transition-all duration-500">
                                <span>Contact Us</span>
                            </button>
                            <button
                                class="py-3 w-full rounded-e-md border border-white/60 text-white font-semibold text-sm hover:bg-blue-600 hover:border-blue-600 transition-all duration-500">
                                <span>Notify me</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</html>
