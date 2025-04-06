<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styled Movie Display</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        #backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            filter: blur(8px);
            transition: opacity 1.5s ease-in-out;
        }

        .container {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 15px;
            max-width: 1000px;
            width: 90%;
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 1.5s ease-in-out, transform 1.5s ease-in-out;
        }

        .show {
            opacity: 1;
            transform: scale(1);
        }

        .poster {
            width: 250px;
            border-radius: 10px;
            margin-right: 30px;
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 1.5s ease-in-out, transform 1.5s ease-in-out;
        }

        .fade-in {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        .info {
            flex: 1;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .details {
            font-size: 16px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .details .rating {
            color: gold;
            display: flex;
            align-items: center;
        }

        .genres {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .genre-tag {
            background-color: #333;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            transition: opacity 1.5s ease-in-out, transform 1.5s ease-in-out;
        }

        .description {
            font-size: 14px;
            line-height: 1.5;
            color: #ccc;
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 1.5s ease-in-out, transform 1.5s ease-in-out;
        }
    </style>
</head>

<body>
    <img id="backdrop">

    <div class="container" id="container">
        <img id="poster" class="poster">
        <div class="info" id="movie-info"></div>
    </div>

    <script>
        const apiKey = "6b8e3eaa1a03ebb45642e9531d8a76d2";
        let currentIndex = 0;
        let movieIds = [];

        async function fetchPopularMovies() {
            const response = await fetch(`https://api.themoviedb.org/3/discover/movie?api_key=${apiKey}&sort_by=popularity.desc&language=en-US`);
            const data = await response.json();
            movieIds = data.results.map(movie => movie.id);
        }

        async function updateContent() {
            const movieId = movieIds[currentIndex];
            const backdrop = document.getElementById('backdrop');
            const poster = document.getElementById('poster');
            const info = document.getElementById('movie-info');
            const container = document.getElementById('container');

            const response = await fetch(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&language=en-US`);
            const data = await response.json();

            const posterUrl = `https://image.tmdb.org/t/p/w500${data.poster_path}`;
            const backdropUrl = `https://image.tmdb.org/t/p/original${data.backdrop_path}`;

            backdrop.src = backdropUrl;
            poster.src = posterUrl;

            info.innerHTML = `
                <div class="title">${data.title}</div>
                <div class="details">
                    <div>${data.release_date.substring(0, 4)}</div>
                    <div>${data.runtime} min</div>
                    <div class="rating">⭐ ${data.vote_average}</div>
                </div>
                <div class="genres">
                    ${data.genres.slice(0, 3).map(genre => `<div class="genre-tag">${genre.name}</div>`).join('')}
                </div>
                <div class="description">${data.overview}</div>
            `;

            // Start fade-in animations
            backdrop.style.opacity = 1;
            container.classList.add('show');
            poster.classList.add('fade-in');
            info.querySelector('.description').style.opacity = 1;
            info.querySelector('.description').style.transform = 'translateX(0)';

            // Reset animations for the next transition
            setTimeout(() => {
                poster.classList.remove('fade-in');
                container.classList.remove('show');
            }, 5000);

            currentIndex = (currentIndex + 1) % movieIds.length;
        }

        fetchPopularMovies().then(() => {
            setInterval(updateContent, 7000);
            updateContent();
        });
    </script>
</body>

</html>
