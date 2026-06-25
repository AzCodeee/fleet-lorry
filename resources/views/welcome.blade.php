<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0f1e;
            overflow: hidden;
            height: 100vh;
            margin: 0;
        }

        /* ── Starfield / road grid background ── */
        .bg-scene {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 0%, #0f2a4a 0%, transparent 65%),
                radial-gradient(ellipse 60% 40% at 80% 80%, #0d1f35 0%, transparent 60%),
                #080c18;
        }

        /* Animated perspective road grid */
        .road-grid {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 260vw;
            height: 55vh;
            background-image:
                linear-gradient(to bottom, transparent 0%, #0e2040 100%),
                repeating-linear-gradient(90deg,
                    transparent 0px,
                    transparent 98px,
                    rgba(255,200,60,0.08) 98px,
                    rgba(255,200,60,0.08) 100px
                ),
                repeating-linear-gradient(180deg,
                    transparent 0px,
                    transparent 58px,
                    rgba(255,255,255,0.04) 58px,
                    rgba(255,255,255,0.04) 60px
                );
            transform-origin: bottom center;
            transform: translateX(-50%) perspective(500px) rotateX(55deg);
            animation: roadScroll 4s linear infinite;
        }

        @keyframes roadScroll {
            from { background-position: 0 0, 0 0, 0 0; }
            to   { background-position: 0 0, 0 0, 0 120px; }
        }

        /* Moving truck silhouettes */
        .truck {
            position: fixed;
            bottom: 12vh;
            width: 120px;
            height: 48px;
            opacity: 0.18;
        }
        .truck svg { width: 100%; height: 100%; }
        .truck-1 { animation: truckLeft 14s linear infinite; left: -140px; }
        .truck-2 { animation: truckLeft 20s linear infinite 6s; left: -140px; opacity: 0.10; transform: scale(0.7); bottom: 8vh; }
        .truck-3 { animation: truckRight 16s linear infinite 2s; right: -140px; }

        @keyframes truckLeft  { from { left: -140px; } to { left: 110%; } }
        @keyframes truckRight { from { right: -140px; } to { right: 110%; } }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.5);
            animation: floatUp var(--dur, 8s) ease-in infinite var(--delay, 0s);
        }
        @keyframes floatUp {
            0%   { transform: translateY(0) translateX(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.4; }
            100% { transform: translateY(-100vh) translateX(var(--dx, 20px)); opacity: 0; }
        }

        /* ── Hero text ── */
        .hero {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 0 24px;
            text-align: center;
        }

        .eyebrow {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.35em;
            color: #f59e0b;
            text-transform: uppercase;
            margin-bottom: 28px;
            opacity: 0;
            animation: fadeSlideDown 0.8s ease forwards 0.3s;
        }

        /* Floating container for the typing text */
        .title-wrap {
            animation: floatBob 5s ease-in-out infinite;
            margin-bottom: 20px;
        }
        @keyframes floatBob {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-14px); }
        }

        .title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2rem, 6vw, 4.2rem);
            font-weight: 700;
            line-height: 1.1;
            color: #ffffff;
            letter-spacing: -0.02em;
            text-shadow: 0 0 60px rgba(59,130,246,0.4), 0 2px 20px rgba(0,0,0,0.6);
            min-height: 1.2em;
        }

        /* Typing cursor */
        .cursor {
            display: inline-block;
            width: 3px;
            height: 1em;
            background: #f59e0b;
            margin-left: 4px;
            vertical-align: middle;
            border-radius: 2px;
            animation: blink 0.75s step-end infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; }
        }

        .subtitle {
            font-size: 1rem;
            color: rgba(148, 163, 184, 0.85);
            max-width: 440px;
            line-height: 1.6;
            margin: 0 auto 48px;
            opacity: 0;
            animation: fadeSlideDown 0.8s ease forwards 0.6s;
        }

        /* Stats row */
        .stats {
            display: flex;
            gap: 36px;
            justify-content: center;
            margin-bottom: 52px;
            opacity: 0;
            animation: fadeSlideDown 0.8s ease forwards 0.9s;
        }
        .stat { text-align: center; }
        .stat-num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #f59e0b;
        }
        .stat-label {
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            color: rgba(148,163,184,0.6);
            text-transform: uppercase;
        }
        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.1);
            align-self: stretch;
        }

        /* CTA Button */
        .cta-wrap {
            opacity: 0;
            animation: fadeSlideDown 0.8s ease forwards 1.2s;
        }

        .btn-login {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 40px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 0 0 0 rgba(59,130,246,0.4), 0 8px 32px rgba(29,78,216,0.5);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 0 6px rgba(59,130,246,0.15), 0 14px 40px rgba(29,78,216,0.6);
        }
        .btn-login:active { transform: translateY(-1px); }
        .btn-login span, .btn-login svg { position: relative; z-index: 1; }

        /* Pulse ring on button */
        .btn-pulse {
            position: absolute;
            inset: -3px;
            border-radius: 50px;
            border: 1.5px solid rgba(59,130,246,0.5);
            animation: pulseRing 2.5s ease-out infinite 1.5s;
        }
        @keyframes pulseRing {
            0%   { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.12); opacity: 0; }
        }

        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Glow orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }
        .orb-1 { width: 500px; height: 500px; background: rgba(29,78,216,0.12); top: -100px; left: -100px; }
        .orb-2 { width: 400px; height: 400px; background: rgba(245,158,11,0.07); bottom: 0; right: -80px; }
        .orb-3 { width: 300px; height: 300px; background: rgba(16,185,129,0.06); top: 40%; left: 50%; transform: translateX(-50%); }
    </style>
</head>
<body>

    <!-- Background layers -->
    <div class="bg-scene"></div>
    <div class="road-grid"></div>

    <!-- Glow orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Floating particles -->
    <div class="particles" id="particles"></div>

    <!-- Truck silhouettes -->
    <div class="truck truck-1">
        <svg viewBox="0 0 120 48" fill="white" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="18" width="72" height="22" rx="2"/>
            <rect x="72" y="8" width="34" height="32" rx="2"/>
            <rect x="106" y="24" width="14" height="16" rx="2"/>
            <circle cx="18" cy="42" r="6"/><circle cx="52" cy="42" r="6"/>
            <circle cx="100" cy="42" r="6"/>
            <rect x="76" y="10" width="24" height="12" rx="1" fill="rgba(0,0,0,0.3)"/>
        </svg>
    </div>
    <div class="truck truck-2">
        <svg viewBox="0 0 120 48" fill="white" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="18" width="72" height="22" rx="2"/>
            <rect x="72" y="8" width="34" height="32" rx="2"/>
            <rect x="106" y="24" width="14" height="16" rx="2"/>
            <circle cx="18" cy="42" r="6"/><circle cx="52" cy="42" r="6"/>
            <circle cx="100" cy="42" r="6"/>
        </svg>
    </div>
    <div class="truck truck-3" style="transform: scaleX(-1);">
        <svg viewBox="0 0 120 48" fill="white" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="18" width="72" height="22" rx="2"/>
            <rect x="72" y="8" width="34" height="32" rx="2"/>
            <rect x="106" y="24" width="14" height="16" rx="2"/>
            <circle cx="18" cy="42" r="6"/><circle cx="52" cy="42" r="6"/>
            <circle cx="100" cy="42" r="6"/>
        </svg>
    </div>

    <!-- Hero content -->
    <div class="hero">

        <p class="eyebrow">
            <svg style="display:inline-block;vertical-align:middle;margin-right:6px;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Powered by AzCode
        </p>

        <div class="title-wrap">
            <h1 class="title" id="typingTitle"><span class="cursor"></span></h1>
        </div>

        {{-- <p class="subtitle">
            Streamline lorry operations, track deliveries, manage drivers and maintenance — all from one powerful platform.
        </p> --}}

        {{-- <div class="stats">
            <div class="stat">
                <div class="stat-num">Real-time</div>
                <div class="stat-label">Fleet Tracking</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <div class="stat-num">360°</div>
                <div class="stat-label">Visibility</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat">
                <div class="stat-num">Smart</div>
                <div class="stat-label">Maintenance</div>
            </div>
        </div> --}}

        <div class="cta-wrap">
            <a href="{{ url('/dashboard') }}" class="btn-login">
                <div class="btn-pulse"></div>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                <span>Get Started — Login</span>
            </a>
        </div>

    </div>

    <script>
        // ── Typing animation ──
        const target   = 'Welcome to Fleet Management System';
        const titleEl  = document.getElementById('typingTitle');
        let   charIdx  = 0;
        let   typed    = '';

        function typeNext() {
            if (charIdx < target.length) {
                typed += target[charIdx++];
                titleEl.innerHTML = typed + '<span class="cursor"></span>';
                // natural typing cadence
                const delay = target[charIdx - 1] === ' ' ? 60 : Math.random() * 60 + 35;
                setTimeout(typeNext, delay);
            }
        }

        // Start after a short pause so the page settles
        setTimeout(typeNext, 800);

        // ── Particles ──
        const container = document.getElementById('particles');
        const colors    = ['rgba(59,130,246,0.6)', 'rgba(245,158,11,0.5)', 'rgba(16,185,129,0.4)', 'rgba(139,92,246,0.5)'];

        for (let i = 0; i < 22; i++) {
            const p    = document.createElement('div');
            const size = Math.random() * 4 + 1.5;
            p.className = 'particle';
            p.style.cssText = `
                width:${size}px; height:${size}px;
                left:${Math.random() * 100}%;
                bottom:${Math.random() * 30}%;
                background:${colors[Math.floor(Math.random()*colors.length)]};
                --dur:${6 + Math.random() * 10}s;
                --delay:${Math.random() * 8}s;
                --dx:${(Math.random() - 0.5) * 80}px;
            `;
            container.appendChild(p);
        }
    </script>
</body>
</html>