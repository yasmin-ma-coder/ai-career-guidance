<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IntelliPath</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f7f8fc;
            color:#1f1f1f;
        }

        .navbar-custom{
            background:#ffffff;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
        }

        .brand-text{
            font-size:30px;
            font-weight:800;
            color:#1f2a7a;
            line-height:1;
        }

        .brand-sub{
            font-size:12px;
            color:#444;
            margin-top:2px;
        }

        .nav-link{
            color:#1f1f1f !important;
            font-weight:500;
            margin-left:12px;
        }

        .hero-section{
            background:linear-gradient(rgba(245,248,255,0.88), rgba(245,248,255,0.88)),
                       url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;
            min-height:88vh;
            display:flex;
            align-items:center;
            padding:60px 0;
        }

        .hero-title{
            font-size:62px;
            font-weight:800;
            color:#1f2a7a;
            line-height:1.1;
        }

        .highlight-gold{
            color:#d4a514;
        }

        .hero-text{
            font-size:21px;
            color:#444;
            margin-top:18px;
            max-width:650px;
        }

        .btn-gold{
            background:#d4a514;
            color:#fff;
            border:none;
            font-weight:600;
            padding:12px 28px;
            border-radius:10px;
        }

        .btn-gold:hover{
            background:#b98d10;
            color:#fff;
        }

        .btn-blue{
            background:#1f2a7a;
            color:#fff;
            border:none;
            font-weight:600;
            padding:12px 28px;
            border-radius:10px;
        }

        .btn-blue:hover{
            background:#16205e;
            color:#fff;
        }

        .hero-circle-wrap{
            position:relative;
            width:460px;
            height:460px;
            margin:auto;
        }

        .hero-circle-top{
            position:absolute;
            width:360px;
            height:360px;
            background:#d4a514;
            border-radius:50%;
            top:0;
            left:50px;
            z-index:1;
        }

        .hero-circle-bottom{
            position:absolute;
            width:420px;
            height:220px;
            background:#1f2a7a;
            border-bottom-left-radius:220px;
            border-bottom-right-radius:220px;
            border-top-left-radius:0;
            border-top-right-radius:0;
            bottom:0;
            left:20px;
            z-index:1;
        }

        .section-title{
            font-size:54px;
            font-weight:800;
            color:#1f2a7a;
            text-align:center;
        }

        .section-subtitle{
            text-align:center;
            color:#555;
            font-size:20px;
            margin-top:12px;
        }

        .why-section{
            background:linear-gradient(rgba(250,250,250,0.92), rgba(250,250,250,0.92)),
                       url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;
            padding:90px 0;
        }

        .why-box{
            background:#fff;
            border:2px solid #2b2e83;
            border-radius:26px;
            padding:22px 28px;
            display:flex;
            align-items:center;
            margin-bottom:22px;
            box-shadow:0 10px 20px rgba(0,0,0,0.05);
        }

        .why-number{
            min-width:88px;
            height:88px;
            border-radius:18px;
            background:linear-gradient(90deg, #2b2e83, #d4a514);
            color:#fff;
            font-size:38px;
            font-weight:800;
            display:flex;
            align-items:center;
            justify-content:center;
            margin-right:24px;
        }

        .why-text{
            font-size:23px;
            font-weight:500;
            line-height:1.3;
        }

        .why-side-img{
            width:100%;
            border-radius:26px;
            box-shadow:0 15px 30px rgba(0,0,0,0.12);
        }

        .feature-card{
            background:#fff;
            border-radius:22px;
            padding:28px;
            text-align:center;
            height:100%;
            box-shadow:0 10px 24px rgba(0,0,0,0.08);
            border:1px solid #eee;
        }

        .feature-card h4{
            font-weight:700;
            margin-bottom:14px;
            color:#1f2a7a;
        }

        .testimonial-section{
            padding:90px 0;
            background:#f5f8ff;
        }

        .testimonial-card{
            background:#fff;
            border-radius:30px;
            overflow:hidden;
            box-shadow:0 14px 30px rgba(0,0,0,0.08);
        }

        .testimonial-left{
            background:#1f2a7a;
            color:white;
            padding:30px 22px;
            text-align:center;
            height:100%;
        }

        .testimonial-placeholder{
            width:220px;
            height:280px;
            margin:0 auto 18px;
            border-radius:22px;
            border:4px solid #d4a514;
            background:linear-gradient(135deg,#dfe7ff,#9db4ff);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:26px;
            font-weight:700;
            color:#1f2a7a;
        }

        .testimonial-right{
            padding:34px;
            font-size:21px;
            line-height:1.8;
            color:#333;
        }

        .contact-section{
            background:linear-gradient(rgba(20,40,120,0.88), rgba(20,40,120,0.88)),
                       url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;
            color:#fff;
            padding:90px 0;
        }

        .contact-box{
            background:rgba(255,255,255,0.88);
            padding:34px;
            border-radius:26px;
            color:#222;
            box-shadow:0 12px 28px rgba(0,0,0,0.18);
        }

        .contact-left h2{
            font-size:54px;
            font-weight:800;
            line-height:1.15;
        }

        .contact-left p{
            font-size:22px;
            margin-top:18px;
            max-width:580px;
        }

        .footer-custom{
            background:#15246d;
            color:#fff;
            text-align:center;
            padding:32px 0;
        }

        .footer-logo{
            font-size:42px;
            font-weight:800;
            color:#fff;
        }

        .footer-sub{
            font-size:18px;
            color:#dcdcdc;
        }

        @media(max-width:991px){
            .hero-title{
                font-size:42px;
                text-align:center;
            }

            .hero-text{
                text-align:center;
                margin-left:auto;
                margin-right:auto;
            }

            .hero-buttons{
                text-align:center;
            }

            .hero-circle-wrap{
                width:320px;
                height:360px;
                margin-top:40px;
            }

            .hero-circle-top{
                width:250px;
                height:250px;
                left:35px;
            }

            .hero-circle-bottom{
                width:300px;
                height:160px;
                left:10px;
            }

            .section-title{
                font-size:38px;
            }

            .contact-left h2{
                font-size:38px;
            }

            .testimonial-right{
                font-size:17px;
            }

            .why-text{
                font-size:18px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom py-3">
    <div class="container">
        <div>
            <div class="brand-text">INTELLIPATH</div>
            <div class="brand-sub">Navigate . Discover . Succeed</div>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#why">Why Choose Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonial">Testimonial</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <a href="login.php" class="btn btn-blue me-2">Login</a>
                    <a href="register.php" class="btn btn-gold">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero-section" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    Navigate <br>
                    <span class="highlight-gold">Your Future</span><br>
                    With Confidence
                </h1>

                <p class="hero-text">
                    Intelligent Career Roadmap helps students discover suitable careers,
                    compare opportunities, receive mentor guidance, and build a clear roadmap
                    for future success.
                </p>

                <div class="hero-buttons mt-4">
                    <a href="register.php" class="btn btn-gold">Get Started</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero-circle-wrap">
                    <div class="hero-circle-top"></div>
                    <div class="hero-circle-bottom"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="why-section" id="why">
    <div class="container">
        <h2 class="section-title">Why Choose<br><span class="highlight-gold">IntelliPath?</span></h2>
        <p class="section-subtitle">The Need for Career Guidance Today</p>

        <div class="row align-items-center mt-5">
            <div class="col-lg-7">
                <div class="why-box">
                    <div class="why-number">01</div>
                    <div class="why-text">Students often choose careers without clarity and proper direction.</div>
                </div>

                <div class="why-box">
                    <div class="why-number">02</div>
                    <div class="why-text">Many students feel confused about which skills and opportunities fit them best.</div>
                </div>

                <div class="why-box">
                    <div class="why-number">03</div>
                    <div class="why-text">Mentor guidance and structured roadmaps help students make better decisions.</div>
                </div>
            </div>

            <div class="col-lg-5">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1000&auto=format&fit=crop" class="why-side-img" alt="Why choose us">
            </div>
        </div>
    </div>
</section>

<section class="py-5" id="features">
    <div class="container py-4">
        <h2 class="section-title">System Features</h2>
        <p class="section-subtitle">Smart tools for better career decisions</p>

        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Career Assessment Test</h4>
                    <p>Students answer structured questions and receive a career recommendation based on intelligent scoring.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Career Roadmap</h4>
                    <p>Get step-by-step guidance on what to study, what skills to learn, and what projects to build.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Career Comparison</h4>
                    <p>Compare two careers using salary, demand, growth rate, tools, and required skills.</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="feature-card">
                    <h4>Mentor Guidance</h4>
                    <p>Book appointments with mentors and receive personalized feedback for career growth.</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="feature-card">
                    <h4>Admin Management</h4>
                    <p>Admin can manage users, questions, careers, mentor feedback, and overall system flow.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="testimonial-section" id="testimonial">
    <div class="container">
        <h2 class="section-title mb-5">Expert Recommendation</h2>

        <div class="testimonial-card">
            <div class="row g-0">
                <div class="col-lg-4">
                    <div class="testimonial-left">
                       <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop"
class="img-fluid rounded" style="width:220px;height:280px;object-fit:cover;border:4px solid #d4a514;">
                        <h4 class="mt-3 mb-1">Academic Expert</h4>
                        <p class="mb-0">Career Guidance Support</p>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="testimonial-right">
                        Intelligent Career Roadmap helps students make informed decisions by connecting
                        their interests with suitable opportunities. It bridges the gap between
                        uncertainty and a structured future plan. With career assessment, roadmaps,
                        mentor guidance, and comparison tools, students can choose their path with
                        greater confidence and clarity.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-section" id="contact">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 contact-left">
                <h2>Find the Right Path for Your Future</h2>
                <p>
                    Connect with us for career assessments, personalized guidance,
                    and structured support designed to help students build brighter futures.
                </p>

                <div class="mt-4">
                    <h4>Email</h4>
                    <p>support@intellipath.com</p>

                    <h4>Phone</h4>
                    <p>+91 98765 43210</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-box">
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-lg" placeholder="Name">
                        </div>

                        <div class="mb-3">
                            <input type="email" class="form-control form-control-lg" placeholder="Email">
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control form-control-lg" placeholder="Mobile Number">
                        </div>

                        <div class="mb-3">
                            <textarea class="form-control form-control-lg" rows="5" placeholder="Message"></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-blue px-5">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-custom">
    <div class="container">
        <div class="footer-logo">INTELLIPATH</div>
        <div class="footer-sub">Intelligent Career Roadmap</div>
        <p class="mt-3 mb-0">© 2026 IntelliPath</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>