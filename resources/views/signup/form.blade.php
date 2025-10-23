<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tilmeld dig {{ $mailingList->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #2F3D50;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> {{ session('info') }}
            </div>
        @endif

        @if ($template === 'simple')
            <div style="background: white; border-radius: 8px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div style="text-align: center; max-width: 400px; margin: 0 auto;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <img src="{{ $data['image'] }}" alt="Logo" style="width: auto; height: auto; max-width: 100%; display: inline-block; margin-bottom: 20px;">
                    </div>
                    <h3 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 10px;">{{ $data['header'] }}</h3>
                    <p style="font-size: 14px; color: #666; margin-bottom: 20px;">{{ $data['body'] }}</p>
                    <form action="{{ route('signup.store', $mailingList->slug) }}" method="POST" style="text-align: left;">
                        @csrf
                        <input type="text" name="name" placeholder="Dit navn" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        @error('name')
                            <p style="color: #dc3545; font-size: 12px; margin-top: -8px; margin-bottom: 10px;">{{ $message }}</p>
                        @enderror
                        <input type="email" name="email" placeholder="Din email" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
                        @error('email')
                            <p style="color: #dc3545; font-size: 12px; margin-top: -13px; margin-bottom: 15px;">{{ $message }}</p>
                        @enderror
                        <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                            Tilmeld
                        </button>
                    </form>
                </div>
            </div>
        @elseif ($template === 'modern')
            <div style="background: white; border-radius: 8px; overflow: hidden; max-width: 400px; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <img src="{{ $data['image'] }}" alt="Header" style="width: 100%; height: 150px; object-fit: cover;">
                <div style="padding: 30px;">
                    <h3 style="font-size: 22px; font-weight: 600; color: #333; margin-bottom: 10px;">{{ $data['header'] }}</h3>
                    <p style="font-size: 14px; color: #666; margin-bottom: 20px;">{{ $data['body'] }}</p>
                    <form action="{{ route('signup.store', $mailingList->slug) }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="Navn" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; border-radius: 4px;">
                        @error('name')
                            <p style="color: #dc3545; font-size: 12px; margin-top: -8px; margin-bottom: 10px;">{{ $message }}</p>
                        @enderror
                        <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px;">
                        @error('email')
                            <p style="color: #dc3545; font-size: 12px; margin-top: -13px; margin-bottom: 15px;">{{ $message }}</p>
                        @enderror
                        <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                            Start nu
                        </button>
                    </form>
                </div>
            </div>
        @elseif ($template === 'split')
            <div style="background: white; border-radius: 8px; overflow: hidden; max-width: 700px; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; align-items: stretch; min-height: 450px;">
                <div style="flex: 1; background: var(--primary-color); position: relative; min-width: 300px;">
                    <img src="{{ $data['image'] }}" alt="Side" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                </div>
                <div style="flex: 1; padding: 50px 40px; display: flex; flex-direction: column; justify-content: center;">
                    <h3 style="font-size: 26px; font-weight: 600; color: #333; margin-bottom: 12px;">{{ $data['header'] }}</h3>
                    <p style="font-size: 15px; color: #666; margin-bottom: 25px;">{{ $data['body'] }}</p>
                    <form action="{{ route('signup.store', $mailingList->slug) }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="Navn" required style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #e0e0e0; border-radius: 4px;">
                        @error('name')
                            <p style="color: #dc3545; font-size: 12px; margin-top: -10px; margin-bottom: 12px;">{{ $message }}</p>
                        @enderror
                        <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 12px; margin-bottom: 18px; border: 1px solid #e0e0e0; border-radius: 4px;">
                        @error('email')
                            <p style="color: #dc3545; font-size: 12px; margin-top: -16px; margin-bottom: 18px;">{{ $message }}</p>
                        @enderror
                        <button type="submit" style="width: 100%; padding: 14px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer; font-size: 15px;">
                            Tilmeld
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
