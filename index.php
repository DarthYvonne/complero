<!DOCTYPE html>
<html lang="da">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complicero - Vi gør det nemme let</title>
    <meta name="description" content="Få du migræne af din nuværende kursusplatform? Complicero gør det nemme let med intuitivt design og logisk navigation.">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #2c3e50;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #e91e63;
        }

        /* Hero Section */
        .hero {
            padding: 120px 0 80px;
            text-align: center;
            background: #2c3e50;
            color: white;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
        }

        .hero .subtitle {
            font-size: 1.5rem;
            color: #e91e63;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .hero p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .cta-button {
            display: inline-block;
            background: #e91e63;
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .cta-button:hover {
            background: #c2185b;
            transform: translateY(-2px);
        }

        /* Problem Section */
        .problem {
            padding: 80px 0;
            background: white;
        }

        .problem h2 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 60px;
            color: #2c3e50;
        }

        .problem-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .problem-item {
            text-align: center;
            padding: 40px 20px;
            border-radius: 12px;
            background: #f8f9fa;
        }

        .problem-item h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #e91e63;
        }

        .problem-item p {
            font-size: 1.1rem;
            color: #6c757d;
        }

        /* Solution Section */
        .solution {
            padding: 80px 0;
            background: linear-gradient(135deg, #e91e63 0%, #ad1457 100%);
            color: white;
        }

        .solution h2 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 60px;
        }

        .solution-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .solution-item {
            text-align: center;
            padding: 40px 20px;
        }

        .solution-item h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .solution-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: white;
        }

        .features h2 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 60px;
            color: #2c3e50;
        }

        .features-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .feature-item {
            display: flex;
            align-items: center;
            padding: 30px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-check {
            width: 60px;
            height: 60px;
            background: #e91e63;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 30px;
            flex-shrink: 0;
        }

        .feature-check::after {
            content: "✓";
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .feature-content h3 {
            font-size: 1.3rem;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .feature-content p {
            color: #6c757d;
            font-size: 1rem;
        }

        /* CTA Section */
        .final-cta {
            padding: 80px 0;
            background: #2c3e50;
            color: white;
            text-align: center;
        }

        .final-cta h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .final-cta p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .final-cta .cta-button {
            background: #e91e63;
            font-size: 1.2rem;
            padding: 20px 50px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero .subtitle {
                font-size: 1.2rem;
            }

            .problem h2,
            .solution h2,
            .features h2,
            .final-cta h2 {
                font-size: 2rem;
            }

            .feature-item {
                flex-direction: column;
                text-align: center;
            }

            .feature-check {
                margin-right: 0;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="container">
            <div class="nav-content">
                <a href="#" class="logo">complicero</a>
                <ul class="nav-links">
                    <li><a href="#problem">Problemet</a></li>
                    <li><a href="#solution">Løsningen</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#start">Kom i gang</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="subtitle">Complicero - gør det lette nemt</div>
            <h1>Får du migræne af din nuværende kursusplatform?</h1>
            <p>Endelig en platform hvor du finder det du leder efter med det samme. Hvor tingene virker som de skal. Hvor du ikke skal kontakte support hver dag.</p>
            <a href="#start" class="cta-button">Få at vide når vi er klar til dig</a>
        </div>
    </section>

    <!-- Problem Section -->
    <section id="problem" class="problem">
        <div class="container">
            <h2>Du kender følelsen...</h2>
            <div class="problem-grid">
                <div class="problem-item">
                    <h3>20 minutter på at uploade et kursus</h3>
                    <p>Fordi knapperne er gemt væk i undermenuers undermenuer, og intet hedder det man forventer</p>
                </div>
                <div class="problem-item">
                    <h3>Support 3 gange om ugen</h3>
                    <p>Fordi basale funktioner kræver en manual og en IT-uddannelse at finde frem til</p>
                </div>
                <div class="problem-item">
                    <h3>Du kan ikke finde dine egne kurser</h3>
                    <p>I det system du selv har oprettet dem i. Det burde ikke være raketvidenskab</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Solution Section -->
    <section id="solution" class="solution">
        <div class="container">
            <h2>Hos os er det anderledes</h2>
            <div class="solution-grid">
                <div class="solution-item">
                    <h3>Upload kursus: 2 klik</h3>
                    <p>Fordi upload-knappen er der hvor du forventer den. På forsiden. Store og tydelig.</p>
                </div>
                <div class="solution-item">
                    <h3>Support svarer på 2 timer</h3>
                    <p>Men du skriver til dem måske en gang om måneden. Fordi tingene bare virker.</p>
                </div>
                <div class="solution-item">
                    <h3>Alle dine kurser på forsiden</h3>
                    <p>Det første du ser når du logger ind. Ikke gemt i en mappe i en mappe i en mappe.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2>Konkrete forskelle</h2>
            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-check"></div>
                    <div class="feature-content">
                        <h3>Menu med 6 punkter, ikke 47</h3>
                        <p>Vi har fjernet alt det overflødige og beholdt det vigtige</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-check"></div>
                    <div class="feature-content">
                        <h3>Knapper der hedder det de gør</h3>
                        <p>"Tilføj kursus" tilføjer et kursus. "Rediger" redigerer. Ikke raketvidenskab.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-check"></div>
                    <div class="feature-content">
                        <h3>Indstillinger der er lette at finde</h3>
                        <p>Alt er placeret logisk hvor du forventer det. Ingen overraskelser.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-check"></div>
                    <div class="feature-content">
                        <h3>Få klik til alt du skal bruge</h3>
                        <p>Vi har designet navigation så du kommer hurtigt frem til det du skal</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-check"></div>
                    <div class="feature-content">
                        <h3>Intuitivt design der giver mening</h3>
                        <p>Bygget så du instinktivt ved hvor tingene er og hvordan de virker</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section id="start" class="final-cta">
        <div class="container">
            <h2>Klar til at slippe for migrænen?</h2>
            <p>Få besked når Complicero er klar til dig. Vi lancerer snart.</p>
            <a href="#" class="cta-button">Få at vide når vi er klar</a>
        </div>
    </section>
</body>

</html>