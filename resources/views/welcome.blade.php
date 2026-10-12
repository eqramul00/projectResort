@php
    $phone = '01700000000';
    $email = 'hello@mclovinresort.com';
    $location = 'McLovin Valley, Rangamati, Bangladesh';

    $facebook = 'https://www.facebook.com/profile.php?id=100078951246241';
    $instagram = 'https://instagram.com/yourpage';
    $youtube = 'https://youtube.com/@yourchannel';
    $whatsapp = 'https://wa.me/8801700000000';

    $logo = asset('images/logo.jpg');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McLovin Resort</title>
    <meta name="description" content="McLovin Resort — luxury comfort, scenic views, and premium stays.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome for actual colored social icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root{
            --bg:#07101f;
            --bg-2:#0b1830;
            --panel:#0f1d37;
            --panel-2:#122445;
            --panel-3:#091427;
            --gold:#d4a93e;
            --gold-2:#f2d57d;
            --text:#f6f7fb;
            --muted:#aeb8cb;
            --line:rgba(255,255,255,.08);
            --line-gold:rgba(212,169,62,.32);
            --shadow:0 24px 60px rgba(0,0,0,.35);
            --max:1280px;
            --green-soft:rgba(58,165,92,.16);
        }

        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{
            margin:0;
            color:var(--text);
            font-family:'Inter',sans-serif;
            background:
                radial-gradient(circle at top left, rgba(212,169,62,.08), transparent 22%),
                radial-gradient(circle at top right, rgba(255,255,255,.04), transparent 16%),
                linear-gradient(180deg, #040b16 0%, #081427 42%, #0a1830 100%);
            overflow-x:hidden;
        }

        a{text-decoration:none;color:inherit}
        img{display:block;max-width:100%}
        button,input,textarea,select{font:inherit}

        .container{
            width:min(var(--max), calc(100% - 32px));
            margin:auto;
        }

        .section{
            padding:90px 0;
        }

        /* HEADER */
        header{
            position:fixed;
            top:0;
            left:0;
            width:100%;
            z-index:100;
            background:rgba(5,12,22,.50);
            backdrop-filter:blur(14px);
            border-bottom:1px solid rgba(255,255,255,.05);
        }

        .nav{
            min-height:82px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .logo-wrap{
            width:56px;
            height:56px;
            border-radius:16px;
            overflow:hidden;
            background:#fff;
            border:1px solid var(--line-gold);
            padding:4px;
            box-shadow:var(--shadow);
        }

        .logo-wrap img{
            width:100%;
            height:100%;
            object-fit:contain;
        }

        .brand-text h2{
            margin:0;
            font-size:22px;
            font-family:'Cormorant Garamond',serif;
            letter-spacing:.3px;
        }

        .brand-text small{
            display:block;
            margin-top:4px;
            color:var(--gold-2);
            letter-spacing:2px;
            text-transform:uppercase;
            font-size:11px;
        }

        .navlinks{
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }

        .navlinks a{
            padding:10px 14px;
            border-radius:999px;
            color:#d7deea;
            font-size:14px;
            transition:.25s ease;
        }

        .navlinks a:hover{
            background:rgba(255,255,255,.08);
            color:#fff;
        }

        .btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-height:48px;
            padding:12px 20px;
            border-radius:999px;
            border:1px solid var(--line);
            transition:.25s ease;
            cursor:pointer;
            font-weight:600;
        }

        .btn:hover{transform:translateY(-2px)}

        .btn-gold{
            background:linear-gradient(180deg, var(--gold-2), var(--gold));
            color:#111;
            font-weight:700;
            border-color:rgba(212,169,62,.5);
            box-shadow:0 14px 28px rgba(212,169,62,.18);
        }

        .btn-outline{
            background:rgba(255,255,255,.04);
            color:#edf2fb;
            border-color:rgba(255,255,255,.16);
        }

        .btn[disabled]{
            opacity:.6;
            cursor:not-allowed;
            transform:none;
        }

        /* HERO */
        .hero{
            position:relative;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:120px 0 70px;
            background:
                linear-gradient(180deg, rgba(2,10,18,.30), rgba(2,10,18,.58)),
                linear-gradient(90deg, rgba(2,10,18,.34), rgba(2,10,18,.18)),
                url('{{ asset('image/cover.jpg') }}') center center / cover no-repeat;
        }

        .hero::before{
            content:"";
            position:absolute;
            inset:0;
            background:
                radial-gradient(circle at top left, rgba(212,169,62,.10), transparent 20%),
                radial-gradient(circle at bottom center, rgba(34,113,64,.22), transparent 30%),
                linear-gradient(180deg, rgba(7,16,31,.16) 0%, rgba(7,16,31,.52) 100%);
        }

        .hero-inner{
            position:relative;
            z-index:2;
            width:100%;
        }

        .hero-topbar{
            display:flex;
            justify-content:center;
            gap:16px;
            flex-wrap:wrap;
            margin-bottom:24px;
        }

        .hero-nav-chip{
            padding:8px 14px;
            border-radius:999px;
            font-size:13px;
            color:#eef2fb;
            background:rgba(9,18,35,.34);
            border:1px solid rgba(255,255,255,.12);
            backdrop-filter:blur(8px);
        }

        .hero-copy{
            text-align:center;
            max-width:840px;
            margin:0 auto;
        }

        .hero-copy h1{
            margin:0 0 18px;
            font-size:clamp(54px, 8vw, 96px);
            line-height:.92;
            letter-spacing:-1.8px;
            font-family:'Cormorant Garamond',serif;
            text-shadow:0 8px 30px rgba(0,0,0,.35);
        }

        .hero-copy p{
            margin:0 auto;
            max-width:700px;
            color:#e4ebf5;
            line-height:1.9;
            font-size:17px;
            text-shadow:0 4px 18px rgba(0,0,0,.28);
        }

        .hero-actions{
            margin-top:28px;
            display:flex;
            justify-content:center;
            gap:14px;
            flex-wrap:wrap;
        }

        /* WELCOME SECTION */
        .welcome-section{
            padding:90px 0 20px;
        }

        .welcome-box{
            display:grid;
            grid-template-columns:1.05fr .95fr;
            gap:28px;
            align-items:center;
            border-radius:32px;
            overflow:hidden;
            border:1px solid rgba(255,255,255,.08);
            background:linear-gradient(180deg, rgba(18,36,69,.92), rgba(9,20,39,.94));
            box-shadow:var(--shadow);
        }

        .welcome-image{
            min-height:420px;
            background:
                linear-gradient(180deg, rgba(5,18,11,.12), rgba(5,18,11,.34)),
                url('{{ asset('images/cover.jpg') }}') center/cover no-repeat;
            position:relative;
        }

        .welcome-image::after{
            content:"";
            position:absolute;
            inset:0;
            background:
                linear-gradient(180deg, rgba(6,18,10,.08), rgba(5,10,18,.22)),
                radial-gradient(circle at top left, rgba(97,212,126,.18), transparent 24%);
        }

        .welcome-content{
            padding:40px 34px;
        }

        .welcome-tag{
            display:inline-block;
            margin-bottom:14px;
            padding:8px 14px;
            border-radius:999px;
            background:var(--green-soft);
            border:1px solid rgba(110,212,142,.18);
            color:#b8efc7;
            font-size:12px;
            letter-spacing:1.2px;
            text-transform:uppercase;
        }

        .welcome-content h2{
            margin:0 0 14px;
            font-family:'Cormorant Garamond',serif;
            font-size:52px;
            line-height:1;
        }

        .welcome-content p{
            margin:0;
            color:var(--muted);
            line-height:1.9;
            font-size:15px;
        }

        .welcome-features{
            margin-top:22px;
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:12px;
        }

        .welcome-feature{
            padding:14px 16px;
            border-radius:18px;
            background:#0a162a;
            border:1px solid rgba(255,255,255,.08);
            color:#e8eef8;
            font-size:14px;
        }

        /* SECTION HEAD */
        .section-head{
            margin-bottom:30px;
        }

        .section-head .eyebrow{
            display:block;
            color:var(--gold-2);
            font-size:12px;
            letter-spacing:1.5px;
            text-transform:uppercase;
            margin-bottom:10px;
        }

        .section-head h2{
            margin:0;
            font-family:'Cormorant Garamond',serif;
            font-size:50px;
            line-height:1.02;
        }

        .section-head p{
            margin:14px 0 0;
            max-width:680px;
            color:var(--muted);
            line-height:1.9;
            font-size:15px;
        }

        /* ROOMS */
        .rooms-card-grid{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:18px;
        }

        .room-card{
            border-radius:24px;
            overflow:hidden;
            border:1px solid rgba(255,255,255,.08);
            background:linear-gradient(180deg, rgba(18,36,69,.92), rgba(9,20,39,.94));
            box-shadow:var(--shadow);
        }

        .room-card-image{
            height:220px;
            background-size:cover;
            background-position:center;
            position:relative;
        }

        .room-card-image::after{
            content:"";
            position:absolute;
            inset:0;
            background:linear-gradient(180deg, rgba(0,0,0,.04), rgba(0,0,0,.30));
        }

        .room-card-body{
            padding:18px;
        }

        .room-card-top{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            flex-wrap:wrap;
            margin-bottom:10px;
        }

        .room-pill,
        .room-price{
            padding:8px 14px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
        }

        .room-pill{
            background:rgba(255,255,255,.08);
            color:#eef2fb;
            border:1px solid rgba(255,255,255,.10);
        }

        .room-price{
            background:linear-gradient(180deg, var(--gold-2), var(--gold));
            color:#111;
        }

        .room-card h3{
            margin:0 0 8px;
            font-size:26px;
            font-family:'Cormorant Garamond',serif;
        }

        .room-card p{
            margin:0;
            color:var(--muted);
            font-size:14px;
            line-height:1.8;
        }

        .room-card-footer{
            margin-top:16px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
        }

        .room-status{
            color:var(--gold-2);
            font-size:14px;
        }

        /* WHY US */
        .why-grid{
            display:grid;
            grid-template-columns:repeat(5,1fr);
            gap:16px;
        }

        .why-card{
            border-radius:24px;
            overflow:hidden;
            border:1px solid rgba(255,255,255,.08);
            background:linear-gradient(180deg, rgba(18,36,69,.92), rgba(9,20,39,.94));
            box-shadow:var(--shadow);
        }

        .why-card-image{
            height:180px;
            background-size:cover;
            background-position:center;
        }

        .why-card-body{
            padding:18px;
        }

        .why-card-body h3{
            margin:0 0 8px;
            font-size:24px;
            font-family:'Cormorant Garamond',serif;
        }

        .why-card-body p{
            margin:0;
            color:var(--muted);
            font-size:13px;
            line-height:1.8;
        }

        /* CONTACT */
        .contact-box{
            display:grid;
            grid-template-columns:1.15fr .85fr;
            gap:24px;
        }

        .contact-panel,
        .social-panel{
            padding:28px;
            border-radius:30px;
            border:1px solid rgba(255,255,255,.08);
            background:linear-gradient(180deg, rgba(18,36,69,.92), rgba(9,20,39,.94));
            box-shadow:var(--shadow);
        }

        .info-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:16px;
        }

        .info-card{
            padding:22px;
            border-radius:22px;
            border:1px solid rgba(255,255,255,.08);
            background:#0a162a;
        }

        .info-card h3{
            margin:0 0 8px;
            font-size:16px;
            color:var(--gold-2);
        }

        .info-card p,
        .info-card a{
            margin:0;
            color:#dde2ea;
            line-height:1.8;
            font-size:14px;
        }

        .social-panel h3{
            margin:0 0 10px;
            font-family:'Cormorant Garamond',serif;
            font-size:36px;
        }

        .social-panel p{
            margin:0 0 18px;
            color:var(--muted);
            line-height:1.9;
            font-size:14px;
        }

        .social-links{
            display:grid;
            gap:12px;
        }

        .social-link{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            border-radius:18px;
            border:1px solid rgba(255,255,255,.08);
            background:#0a162a;
            transition:.25s ease;
            font-weight:600;
        }

        .social-link:hover{
            transform:translateX(2px);
            border-color:rgba(212,169,62,.3);
        }

        .social-link i{
            margin-right:10px;
            font-size:18px;
        }

        .social-link.facebook i{ color:#1877f2; }

        .social-link.instagram i{
            background:linear-gradient(45deg,#f58529,#dd2a7b,#8134af,#515bd4);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            background-clip:text;
        }

        .social-link.youtube i{ color:#ff0000; }
        .social-link.whatsapp i{ color:#25d366; }

        /* FOOTER */
        footer{
            padding:42px 0;
            border-top:1px solid rgba(255,255,255,.05);
        }

        .footer-wrap{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:18px;
            flex-wrap:wrap;
        }

        .footer-wrap p{
            margin:0;
            color:var(--muted);
            font-size:14px;
        }

        @media(max-width:1150px){
            .welcome-box,
            .contact-box{
                grid-template-columns:1fr;
            }

            .why-grid{
                grid-template-columns:repeat(2,1fr);
            }

            .rooms-card-grid{
                grid-template-columns:repeat(2,1fr);
            }

            .welcome-image{
                min-height:320px;
            }
        }

        @media(max-width:850px){
            .section{padding:64px 0}

            .nav{
                flex-direction:column;
                align-items:flex-start;
                padding:12px 0;
                min-height:auto;
            }

            .navlinks{
                width:100%;
                display:grid;
                grid-template-columns:repeat(2,1fr);
                gap:10px;
            }

            .navlinks a{
                text-align:center;
                background:rgba(255,255,255,.04);
                border:1px solid rgba(255,255,255,.06);
            }

            .nav > .btn{width:100%}

            .hero{
                padding:140px 0 70px;
            }

            .hero-copy h1{
                font-size:44px;
                line-height:1.04;
            }

            .hero-copy p{
                font-size:15px;
            }

            .section-head h2{
                font-size:36px;
            }

            .welcome-content h2{
                font-size:38px;
            }

            .info-grid{
                grid-template-columns:1fr;
            }

            .welcome-features{
                grid-template-columns:1fr;
            }
        }

        @media(max-width:640px){
            .container{width:calc(100% - 20px)}

            .navlinks{
                display:flex;
                overflow:auto;
                flex-wrap:nowrap;
                scrollbar-width:none;
            }

            .navlinks::-webkit-scrollbar{display:none}
            .navlinks a{flex:0 0 auto;min-width:max-content}

            .hero-copy h1{font-size:34px}
            .section-head h2{font-size:30px}

            .why-grid,
            .rooms-card-grid{
                grid-template-columns:1fr;
            }

            .welcome-content{
                padding:24px 18px;
            }

            .welcome-image{
                min-height:240px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="container nav">
        <a class="brand" href="#top" aria-label="Go to top">
            <div class="logo-wrap">
                <img src="{{ $logo }}" alt="McLovin Resort Logo">
            </div>
            <div class="brand-text">
                <h2>McLovin Resort</h2>
                <small>Luxury Mountain Stay</small>
            </div>
        </a>

        <nav class="navlinks" aria-label="Primary navigation">
            <a href="#top">Home</a>
            <a href="#welcome">Welcome</a>
            <a href="#rooms">Rooms</a>
            <a href="#whyus">Why Us</a>
            <a href="#contact">Contact</a>
        </nav>

        <a href="{{ route('public.book') }}" class="btn btn-gold">Reserve Now</a>
    </div>
</header>

<section class="hero" id="top">
    <div class="container hero-inner">
        <div class="hero-topbar">
            <span class="hero-nav-chip">Luxury</span>
            <span class="hero-nav-chip">Comfort</span>
            <span class="hero-nav-chip">Nature</span>
        </div>

        <div class="hero-copy">
            <h1>Luxury Hotels</h1>
            <p>
                A refined destination where scenic beauty, peaceful surroundings, and elegant
                comfort create a truly memorable resort experience.
            </p>

            <div class="hero-actions">
                <a href="{{ route('public.book') }}" class="btn btn-gold">Book Now</a>
                <a href="#rooms" class="btn btn-outline">View Rooms</a>
            </div>
        </div>
    </div>
</section>

<section class="welcome-section" id="welcome">
    <div class="container">
        <div class="welcome-box">
            <div class="welcome-image"></div>

            <div class="welcome-content">
                <span class="welcome-tag">Welcome to McLovin Resort</span>
                <h2>A peaceful stay surrounded by greenery</h2>
                <p>
                    Welcome to McLovin Resort, where natural beauty, fresh air, and premium comfort come together.
                    Enjoy a relaxing escape surrounded by green hills, peaceful views, and warm hospitality designed
                    to make every stay memorable.
                </p>

                <div class="welcome-features">
                    <div class="welcome-feature">Green scenic surroundings</div>
                    <div class="welcome-feature">Luxury and comfort</div>
                    <div class="welcome-feature">Quiet relaxing atmosphere</div>
                    <div class="welcome-feature">Perfect for family and couples</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="rooms">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Luxury accommodation</span>
            <h2>Our rooms</h2>
            <p>
                Explore our elegant room options designed for comfort, style, and a peaceful luxury resort experience.
            </p>
        </div>

        <div class="rooms-card-grid">
            @if(isset($rooms) && $rooms->count())
                @foreach($rooms->take(6) as $room)
                    <article class="room-card">
                        <div class="room-card-image" style="background-image:url('@if($room->images->first())/{{ $room->images->first()->image_path }}@else https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1400&q=80 @endif')"></div>

                        <div class="room-card-body">
                            <div class="room-card-top">
                                <span class="room-pill">{{ $room->type ?: 'Luxury Room' }}</span>
                                <span class="room-price">৳ {{ number_format($room->price, 0) }}</span>
                            </div>

                            <h3>{{ $room->name }}</h3>
                            <p>
                                Room {{ $room->number }} • {{ ucfirst($room->status) }}<br><br>
                                Elegant comfort, premium details, and a calm atmosphere designed for memorable stays.
                            </p>

                            <div class="room-card-footer">
                                <div class="room-status">
                                    {{ strtolower($room->status) === 'available' ? 'Available Now' : ucfirst($room->status) }}
                                </div>

                                @if(strtolower($room->status) === 'available')
                                    <a href="{{ route('public.book', ['room' => $room->id]) }}" class="btn btn-gold">Reserve</a>
                                @else
                                    <button class="btn btn-outline" type="button" disabled>Unavailable</button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            @else
                <article class="room-card">
                    <div class="room-card-image" style="background-image:url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1400&q=80')"></div>
                    <div class="room-card-body">
                        <div class="room-card-top">
                            <span class="room-pill">Deluxe Room</span>
                            <span class="room-price">৳ 4,500</span>
                        </div>
                        <h3>Deluxe Room</h3>
                        <p>Elegant comfort with thoughtful details and a warm, peaceful atmosphere.</p>
                        <div class="room-card-footer">
                            <div class="room-status">Available Now</div>
                            <a href="{{ route('public.book') }}" class="btn btn-gold">Reserve</a>
                        </div>
                    </div>
                </article>

                <article class="room-card">
                    <div class="room-card-image" style="background-image:url('https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1400&q=80')"></div>
                    <div class="room-card-body">
                        <div class="room-card-top">
                            <span class="room-pill">Premium Room</span>
                            <span class="room-price">৳ 6,000</span>
                        </div>
                        <h3>Premium Room</h3>
                        <p>More space, refined style, and premium comfort for a memorable resort stay.</p>
                        <div class="room-card-footer">
                            <div class="room-status">Available Now</div>
                            <a href="{{ route('public.book') }}" class="btn btn-gold">Reserve</a>
                        </div>
                    </div>
                </article>

                <article class="room-card">
                    <div class="room-card-image" style="background-image:url('https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1400&q=80')"></div>
                    <div class="room-card-body">
                        <div class="room-card-top">
                            <span class="room-pill">Hill View Suite</span>
                            <span class="room-price">৳ 8,500</span>
                        </div>
                        <h3>Hill View Suite</h3>
                        <p>A luxury suite with refined details, scenic atmosphere, and elevated comfort.</p>
                        <div class="room-card-footer">
                            <div class="room-status">Available Now</div>
                            <a href="{{ route('public.book') }}" class="btn btn-gold">Reserve</a>
                        </div>
                    </div>
                </article>
            @endif
        </div>
    </div>
</section>

<section class="section" id="whyus">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Why choose us</span>
            <h2>Why guests choose McLovin Resort</h2>
            <p>
                Experience comfort, peaceful surroundings, and premium hospitality in one refined destination.
            </p>
        </div>

        <div class="why-grid">
            <article class="why-card">
                <div class="why-card-image" style="background-image:url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1200&q=80')"></div>
                <div class="why-card-body">
                    <h3>Scenic Beauty</h3>
                    <p>Beautiful surroundings and refreshing views for a peaceful escape.</p>
                </div>
            </article>

            <article class="why-card">
                <div class="why-card-image" style="background-image:url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80')"></div>
                <div class="why-card-body">
                    <h3>Elegant Rooms</h3>
                    <p>Designed with warmth, comfort, and premium details.</p>
                </div>
            </article>

            <article class="why-card">
                <div class="why-card-image" style="background-image:url('https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=1200&q=80')"></div>
                <div class="why-card-body">
                    <h3>Peaceful Stay</h3>
                    <p>A calm and quiet atmosphere away from daily noise.</p>
                </div>
            </article>

            <article class="why-card">
                <div class="why-card-image" style="background-image:url('https://images.unsplash.com/photo-1551776235-dde6d4829808?auto=format&fit=crop&w=1200&q=80')"></div>
                <div class="why-card-body">
                    <h3>Warm Hospitality</h3>
                    <p>Friendly service and thoughtful care for every guest.</p>
                </div>
            </article>

            <article class="why-card">
                <div class="why-card-image" style="background-image:url('https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=80')"></div>
                <div class="why-card-body">
                    <h3>Memorable Moments</h3>
                    <p>Perfect for family trips, private stays, and special escapes.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section" id="contact">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Contact</span>
            <h2>Plan your visit with us</h2>
            <p>Reach out for booking inquiries, stay details, or direct support from the resort team.</p>
        </div>

        <div class="contact-box">
            <div class="contact-panel">
                <div class="info-grid">
                    <div class="info-card">
                        <h3>📍 Location</h3>
                        <p>{{ $location }}</p>
                    </div>
                    <div class="info-card">
                        <h3>📞 Phone</h3>
                        <p><a href="tel:{{ $phone }}">{{ $phone }}</a></p>
                    </div>
                    <div class="info-card">
                        <h3>✉ Email</h3>
                        <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
                    </div>
                    <div class="info-card">
                        <h3>🕒 Support</h3>
                        <p>Booking support available for all guests.</p>
                    </div>
                </div>
            </div>

            <div class="social-panel">
                <h3>Follow McLovin Resort</h3>
                <p>Stay connected with us and explore more updates, moments, and resort experiences.</p>

                <div class="social-links">
                    <a href="{{ $facebook }}" class="social-link facebook" target="_blank">
                        <span><i class="fab fa-facebook-f"></i> Facebook</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ $instagram }}" class="social-link instagram" target="_blank">
                        <span><i class="fab fa-instagram"></i> Instagram</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ $youtube }}" class="social-link youtube" target="_blank">
                        <span><i class="fab fa-youtube"></i> YouTube</span>
                        <span>↗</span>
                    </a>

                    <a href="{{ $whatsapp }}" class="social-link whatsapp" target="_blank">
                        <span><i class="fab fa-whatsapp"></i> WhatsApp</span>
                        <span>↗</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container footer-wrap">
        <p>© {{ date('Y') }} McLovin Resort. Luxury stays in a peaceful setting.</p>
        <a href="{{ route('public.book') }}" class="btn btn-gold">Reserve Your Stay</a>
    </div>
</footer>

</body>
</html>