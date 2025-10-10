


<?php
function query($query, $params = [], $fetchSingle = false)
{
    try {
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $con = new PDO($dsn, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stm = $con->prepare($query);
        $stm->execute($params);

        if (stripos($query, 'select') === 0) {
            return $fetchSingle ? $stm->fetch(PDO::FETCH_ASSOC) : $stm->fetchAll(PDO::FETCH_ASSOC);
        }

        return true;
    } catch (PDOException $e) {
        echo "Veritabanı Hatası: " . $e->getMessage();
        return false;
    }
}

function query_row($query, $params = [], $fetchSingleValue = false)
{
    try {
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $con = new PDO($dsn, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stm = $con->prepare($query);
        $stm->execute($params);

        if (stripos($query, 'select') === 0) {

            if ($fetchSingleValue) {
                $row = $stm->fetch(PDO::FETCH_NUM);
                return $row ? $row[0] : false;
            }

            return $stm->fetch(PDO::FETCH_ASSOC);
        }

        return true;
    } catch (PDOException $e) {
        echo "Veritabanı Hatası: " . $e->getMessage();
        return false;
    }
}




function redirect($page)
{
    header("Location: " . ROOT . "/" . $page);
    die;
}



function old_value($key, $default = '')
{
    if (!empty($_POST[$key]))
        return $_POST[$key];

    return $default;
}

function old_checked($key, $default = '')
{
    if (!empty($_POST[$key]))
        return " checked ";
    return "";
}

function authenticate($row)
{
    $_SESSION['user'] = $row;
}

function user($key = '')
{
    if (empty($key))
        return $_SESSION['user'] ?? null;

    return $_SESSION['user'][$key] ?? null;
}



function logged_in()
{
    if (!empty($_SESSION['user']))
        return true;

    return false;
}

function is_admin()
{
    if (!empty($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
        return true;
    }
    return false;
}

function str_to_url($url)
{
    $url = str_replace('"', '', $url);
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);

    return $url;
}

function esc($str)
{
    return htmlspecialchars($str ?? '');
}


function get_images($file)
{
    $file = $file ?? '';
    if (file_exists($file)) {
        return  ROOT . '/' . $file;
    }
    return  ROOT . '/assets/images/no_image.jpg' . $file;
}

function get_pagination_vars()
{
    $page_number = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $page_number = $page_number < 1 ? 1 : $page_number;

    $current_link = $_GET['url'] ?? 'home';
    $current_link = ROOT . "/" . $current_link;
    $query_string = "";

    foreach ($_GET as $key => $value) {
        if ($key != 'url') {
            $query_string .= "&" . $key . '=' . $value;
        }
    }

    if (!strstr($query_string, "page=")) {
        $query_string .= "&page=" . $page_number;
    }

    $query_string = trim($query_string, "&");
    $current_link .= "?" . $query_string;
    $next_link = preg_replace("/page=[0-9]+/", "page=" . ($page_number + 1), $current_link);
    $first_link = preg_replace("/page=[0-9]+/", "page=1", $current_link);

    $prev_page_number_for_link = $page_number > 1 ? $page_number - 1 : 1;
    $prev_link = preg_replace("/page=[0-9]+/", "page=" . $prev_page_number_for_link, $current_link);
    $result = [
        'current_link' => $current_link,
        'next_link' => $next_link,
        'prev_link' => $prev_link,
        'first_link' => $first_link,
        'page_number' => $page_number,

    ];

    return $result;
}


create_tables();

function create_tables()
{
    try {
        $string = "mysql:host=" . DBHOST . ";charset=utf8mb4";
        $con = new PDO($string, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "CREATE DATABASE IF NOT EXISTS " . DBNAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci";
        $con->exec($query);

        $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $con = new PDO($string, DBUSER, DBPASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $con->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) UNIQUE NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                role ENUM('admin','user') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $users_data = [
            ["id" => "10", "username" => "admin", "email" => "admin@IMBd.com", "password_hash" => '$2y$10$wDiAzuBYgD9XAfs.9.PYJuiKsHJKEGTel/Dgr7yc1PvQQGLpTeHdi', "role" => "admin", "created_at" => "2025-06-05 12:18:09"],
            ["id" => "11", "username" => "kullanici", "email" => "kullanici@gmail.com", "password_hash" => '$2y$10$8PDQgB6ztNYH7bnxyqpa8eGzeZGrdq6vtR.VLNx1VYApuTSCS58Ru', "role" => "user", "created_at" => "2025-06-05 12:19:56"]
        ];
        $stmt_users = $con->prepare("INSERT IGNORE INTO users (id, username, email, password_hash, role, created_at) VALUES (:id, :username, :email, :password_hash, :role, :created_at)");
        foreach ($users_data as $user) {
            $stmt_users->execute($user);
        }

        $con->exec("
            CREATE TABLE IF NOT EXISTS actors (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $actors_data = [
            ["id" => "5", "name" => "Bella Ramsey"],
            ["id" => "6", "name" => "Pedro Pascal"],
            ["id" => "7", "name" => "Gabriel Luna"],
            ["id" => "8", "name" => "Nick Offerman"],
            ["id" => "9", "name" => "Murray Bartlett"],
            ["id" => "10", "name" => "Anna Torv"],
            ["id" => "11", "name" => "Merle Dandridge"],
            ["id" => "12", "name" => "Jeffrey Pierce"],
            ["id" => "13", "name" => "Young Mazino"],
            ["id" => "14", "name" => "Storm Reid"]
        ];
        $stmt_actors = $con->prepare("INSERT IGNORE INTO actors (id, name) VALUES (:id, :name)");
        foreach ($actors_data as $actor) {
            $stmt_actors->execute($actor);
        }

        $con->exec("
            CREATE TABLE IF NOT EXISTS genres (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(255) DEFAULT NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $genres_data = [
            ["id" => "16", "name" => "Distopya Bilimkurgu", "slug" => "distopya-bilimkurgu"],
            ["id" => "17", "name" => "Arayış", "slug" => "arayis"],
            ["id" => "18", "name" => "Hayatta Kalma", "slug" => "hayatta-kalma"],
            ["id" => "19", "name" => "Zombi", "slug" => "zombi"],
            ["id" => "20", "name" => "Korku", "slug" => "korku"],
            ["id" => "21", "name" => "Aksiyon", "slug" => "aksiyon"],
            ["id" => "22", "name" => "Macera", "slug" => "macera"],
            ["id" => "23", "name" => "Drama", "slug" => "drama"],
            ["id" => "24", "name" => "Bilim Kurgu", "slug" => "bilim-kurgu"],
            ["id" => "25", "name" => "Gerilim", "slug" => "gerilim"],
            ["id" => "26", "name" => "Suç", "slug" => "suc"],
            ["id" => "27", "name" => "Gizem", "slug" => "gizem"],
            ["id" => "28", "name" => "Hapishane", "slug" => "hapishane"],
            ["id" => "30", "name" => "Epik", "slug" => "epik"],
            ["id" => "31", "name" => "Mafya", "slug" => "mafya"],
            ["id" => "32", "name" => "Trajedi", "slug" => "trajedi"],
            ["id" => "33", "name" => "Süper Kahraman", "slug" => "super-kahraman"],
            ["id" => "34", "name" => "Animasyon", "slug" => "animasyon"],
            ["id" => "35", "name" => "Parodi", "slug" => "parodi"],
            ["id" => "36", "name" => "Komedi", "slug" => "komedi"],
            ["id" => "37", "name" => "Zaman Yolculuğu", "slug" => "zaman-yolculugu"]
        ];
        $stmt_genres = $con->prepare("INSERT IGNORE INTO genres (id, name, slug) VALUES (:id, :name, :slug)");
        foreach ($genres_data as $genre) {
            $stmt_genres->execute($genre);
        }

        $con->exec("
            CREATE TABLE IF NOT EXISTS movies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) DEFAULT NULL UNIQUE,
                description TEXT,
                release_year DATE DEFAULT NULL,
                duration_minutes INT DEFAULT NULL,
                rating FLOAT DEFAULT NULL,
                poster_url VARCHAR(255) DEFAULT NULL,
                type ENUM('movie','series') DEFAULT 'movie',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                vote_count INT DEFAULT 0 
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $movies_data = [
            ["id" => "182", "title" => "The Last of Us", "description" => "Küresel bir salgının uygarlığı yok etmesinin ardından, hayatta kalmayı başaran sert bir adam, insanlığın son umudu olabilecek 14 yaşındaki bir kızın sorumluluğunu üstlenir.", "release_year" => "2022-09-02", "duration_minutes" => "50", "rating" => null, "poster_url" => "assets/images/thumbnail/1749045495_the_last-of_us.jpg", "created_at" => "2025-06-04 16:54:16", "slug" => "the-last-of-us", "type" => "series"],
            ["id" => "183", "title" => "The Walking Dead", "description" => "Şerif Yardımcısı Rick Grimes komadan uyandığında dünyanın harabeye döndüğünü öğrenir ve hayatta kalmak için bir grup kurtulana liderlik etmesi gerekir.", "release_year" => "2010-10-31", "duration_minutes" => "45", "rating" => null, "poster_url" => "assets/images/thumbnail/1749045937_the_walking_dead.jpg", "created_at" => "2025-06-04 17:04:27", "slug" => "the-walking-dead", "type" => "series"],
            ["id" => "184", "title" => "Breaking Bad", "description" => "Ameliyat edilemez akciğer kanseri teşhisi konan bir kimya öğretmeni, ailesinin geleceğini güvence altına almak için eski bir öğrencisiyle birlikte metamfetamin üretip satmaya başlar.", "release_year" => "2008-01-20", "duration_minutes" => "45", "rating" => null, "poster_url" => "assets/images/thumbnail/1749046223_breaking_bad-jpg.jpg", "created_at" => "2025-06-04 17:10:23", "slug" => "breaking-bad", "type" => "series"],
            ["id" => "185", "title" => "Chernobyl", "description" => "Nisan 1986'da Sovyetler Birliği'ndeki Çernobil şehri insanlık tarihinin en kötü nükleer felaketlerinden birine maruz kalır. Bunun sonucunda, takip eden günler, haftalar ve aylar boyunca pek çok kahraman hayatını ortaya koyar.", "release_year" => "2019-05-06", "duration_minutes" => "60", "rating" => null, "poster_url" => "assets/images/thumbnail/1749046387_chernobyl-jpg.jpg", "created_at" => "2025-06-04 17:13:07", "slug" => "chernobyl", "type" => "series"],
            ["id" => "186", "title" => "Sherlock", "description" => "Sir Arthur Conan Doyle'un Sherlock Holmes romanlarının modernize edilmiş bir dizi uyarlaması. Bir ordu doktoru olan John Watson (Martin Freeman) Afganistan'da yaralanır ve ordudan atılır. Londra'ya döndüğünde kalacak bir yer arayışı, onu zeki ama eksantrik bir özel dedektif olan Sherlock Holmes (Benedict Cumberbatch) ile aynı daireyi paylaşmaya yönlendirir. Birlikte şaşırtıcı ve çoğu zaman tuhaf cinayet gizemlerini çözerler. Ayrıca Holmes'un baş düşmanı, suç dehası Moriarty ile de mücadele etmek zorundadırlar.", "release_year" => "2010-10-24", "duration_minutes" => "90", "rating" => null, "poster_url" => "assets/images/thumbnail/1749046822_sherlock-jpg.jpg", "created_at" => "2025-06-04 17:20:22", "slug" => "sherlock", "type" => "series"],
            ["id" => "187", "title" => "The Shawshank Redemption", "description" => "Uxoricide suçundan hüküm giymiş bir bankacı, masumiyetini korurken ve basit bir şefkatle umutlu kalmaya çalışırken, sertleşmiş bir mahkûmla çeyrek asırlık bir dostluk kurar.", "release_year" => "1994-10-14", "duration_minutes" => "142", "rating" => null, "poster_url" => "assets/images/thumbnail/1749047155_the-shawshank-redemption-jpg.jpg", "created_at" => "2025-06-04 17:25:55", "slug" => "the-shawshank-redemption", "type" => "movie"],
            ["id" => "188", "title" => "The Godfather", "description" => "Organize suç hanedanının yaşlanan reisi, gizli imparatorluğunun kontrolünü isteksiz oğluna devreder.", "release_year" => "1972-03-24", "duration_minutes" => "175", "rating" => null, "poster_url" => "assets/images/thumbnail/1749047443_the_godfather-jpg.jpg", "created_at" => "2025-06-04 17:30:43", "slug" => "the-godfather", "type" => "movie"],
            ["id" => "189", "title" => "The Dark Knight", "description" => "Joker olarak bilinen bir tehdit Gotham halkına zarar verip kaos yarattığında, Batman, James Gordon ve Harvey Dent bu çılgınlığa bir son vermek için birlikte çalışmak zorunda kalır.", "release_year" => "2008-07-18", "duration_minutes" => "152", "rating" => null, "poster_url" => "assets/images/thumbnail/1749047659_the-dark-knight-jpg.jpg", "created_at" => "2025-06-04 17:34:19", "slug" => "the-dark-knight", "type" => "movie"],
            ["id" => "190", "title" => "the lord of the rings the fellowship of the ring", "description" => "Shire'lı uysal bir Hobbit ve sekiz yol arkadaşı, güçlü Tek Yüzük'ü yok etmek ve Orta Dünya'yı Karanlık Lord Sauron'dan kurtarmak için bir yolculuğa çıkarlar.", "release_year" => "2001-12-19", "duration_minutes" => "178", "rating" => null, "poster_url" => "assets/images/thumbnail/1749048111_the-lord-of-the-rings-the-fellowship-of-the-ring-jpg.jpg", "created_at" => "2025-06-04 17:41:51", "slug" => "the-lord-of-the-rings-the-fellowship-of-the-ring", "type" => "movie"],
            ["id" => "191", "title" => "The Matrix", "description" => "Güzel bir yabancı, bilgisayar korsanı Neo'yu yasak bir yeraltı dünyasına götürdüğünde, şok edici gerçeği keşfeder - bildiği hayat, kötü bir siber istihbaratın ayrıntılı bir aldatmacasıdır.", "release_year" => "1999-03-31", "duration_minutes" => "136", "rating" => "0", "poster_url" => "assets/images/thumbnail/1749048250_matrix-jpg.jpg", "created_at" => "2025-06-04 17:44:10", "slug" => "the-matrix", "type" => "movie"],
            ["id" => "192", "title" => "Interstellar", "description" => "Gelecekte Dünya yaşanmaz hale geldiğinde, bir çiftçi ve eski NASA pilotu olan Joseph Cooper, insanlar için yeni bir gezegen bulmak üzere bir araştırma ekibiyle birlikte bir uzay aracına pilotluk yapmakla görevlendirilir.", "release_year" => "2014-11-07", "duration_minutes" => "169", "rating" => null, "poster_url" => "assets/images/thumbnail/1749048498_interstellar-jpg.jpg", "created_at" => "2025-06-04 17:48:18", "slug" => "interstellar", "type" => "movie"],
            ["id" => "193", "title" => "Rick and Morty", "description" => "Nihilist bir çılgın bilim adamı ile endişeli torununun parçalanmış aile yaşamları, boyutlar arası yanlış maceralarıyla daha da karmaşık hale gelir.", "release_year" => "2013-12-02", "duration_minutes" => "23", "rating" => null, "poster_url" => "assets/images/thumbnail/1749048703_rick-and-morty-jpg.jpg", "created_at" => "2025-06-04 17:51:43", "slug" => "rick-and-morty", "type" => "series"]
        ];
        $stmt_movies = $con->prepare("INSERT IGNORE INTO movies (id, title, slug, description, release_year, duration_minutes, rating, poster_url, type, created_at) VALUES (:id, :title, :slug, :description, :release_year, :duration_minutes, :rating, :poster_url, :type, :created_at)");
        foreach ($movies_data as $movie) {
            if ($movie['release_year'] === '0000-00-00') {
                $movie['release_year'] = null;
            }
            $stmt_movies->execute($movie);
        }

        $con->exec("
            CREATE TABLE IF NOT EXISTS movie_actors (
                movie_id INT NOT NULL,
                actor_id INT NOT NULL,
                PRIMARY KEY (movie_id, actor_id),
                FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
                FOREIGN KEY (actor_id) REFERENCES actors(id) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $movie_actors_data = [
            ["movie_id" => "184", "actor_id" => "5"],
            ["movie_id" => "187", "actor_id" => "5"],
            ["movie_id" => "188", "actor_id" => "5"],
            ["movie_id" => "191", "actor_id" => "5"],
            ["movie_id" => "184", "actor_id" => "6"],
            ["movie_id" => "185", "actor_id" => "6"],
            ["movie_id" => "186", "actor_id" => "6"],
            ["movie_id" => "192", "actor_id" => "6"],
            ["movie_id" => "184", "actor_id" => "7"],
            ["movie_id" => "186", "actor_id" => "7"],
            ["movie_id" => "187", "actor_id" => "7"],
            ["movie_id" => "188", "actor_id" => "7"],
            ["movie_id" => "189", "actor_id" => "7"],
            ["movie_id" => "191", "actor_id" => "7"],
            ["movie_id" => "193", "actor_id" => "7"],
            ["movie_id" => "184", "actor_id" => "8"],
            ["movie_id" => "185", "actor_id" => "8"],
            ["movie_id" => "186", "actor_id" => "8"],
            ["movie_id" => "190", "actor_id" => "8"],
            ["movie_id" => "192", "actor_id" => "8"],
            ["movie_id" => "184", "actor_id" => "9"],
            ["movie_id" => "193", "actor_id" => "9"],
            ["movie_id" => "184", "actor_id" => "10"],
            ["movie_id" => "186", "actor_id" => "10"],
            ["movie_id" => "187", "actor_id" => "10"],
            ["movie_id" => "189", "actor_id" => "10"],
            ["movie_id" => "190", "actor_id" => "10"],
            ["movie_id" => "191", "actor_id" => "10"],
            ["movie_id" => "192", "actor_id" => "10"],
            ["movie_id" => "193", "actor_id" => "10"],
            ["movie_id" => "184", "actor_id" => "11"],
            ["movie_id" => "192", "actor_id" => "11"],
            ["movie_id" => "193", "actor_id" => "11"],
            ["movie_id" => "184", "actor_id" => "12"],
            ["movie_id" => "186", "actor_id" => "12"],
            ["movie_id" => "189", "actor_id" => "12"],
            ["movie_id" => "193", "actor_id" => "12"],
            ["movie_id" => "184", "actor_id" => "13"],
            ["movie_id" => "185", "actor_id" => "13"],
            ["movie_id" => "187", "actor_id" => "13"],
            ["movie_id" => "190", "actor_id" => "13"],
            ["movie_id" => "192", "actor_id" => "13"],
            ["movie_id" => "184", "actor_id" => "14"],
            ["movie_id" => "188", "actor_id" => "14"],
            ["movie_id" => "189", "actor_id" => "14"],
            ["movie_id" => "190", "actor_id" => "14"],
            ["movie_id" => "191", "actor_id" => "14"],
            ["movie_id" => "193", "actor_id" => "14"]
        ];
        $stmt_movie_actors = $con->prepare("INSERT IGNORE INTO movie_actors (movie_id, actor_id) VALUES (:movie_id, :actor_id)");
        foreach ($movie_actors_data as $ma_data) {
            $stmt_movie_actors->execute($ma_data);
        }

        $con->exec("
            CREATE TABLE IF NOT EXISTS movie_genres (
                movie_id INT NOT NULL,
                genre_id INT NOT NULL,
                PRIMARY KEY (movie_id, genre_id),
                FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
                FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $movie_genres_data = [
            ["movie_id" => "182", "genre_id" => "16"],
            ["movie_id" => "182", "genre_id" => "17"],
            ["movie_id" => "192", "genre_id" => "17"],
            ["movie_id" => "193", "genre_id" => "17"],
            ["movie_id" => "182", "genre_id" => "18"],
            ["movie_id" => "182", "genre_id" => "19"],
            ["movie_id" => "183", "genre_id" => "19"],
            ["movie_id" => "182", "genre_id" => "20"],
            ["movie_id" => "183", "genre_id" => "20"],
            ["movie_id" => "182", "genre_id" => "21"],
            ["movie_id" => "189", "genre_id" => "21"],
            ["movie_id" => "190", "genre_id" => "21"],
            ["movie_id" => "191", "genre_id" => "21"],
            ["movie_id" => "193", "genre_id" => "21"],
            ["movie_id" => "182", "genre_id" => "22"],
            ["movie_id" => "190", "genre_id" => "22"],
            ["movie_id" => "192", "genre_id" => "22"],
            ["movie_id" => "193", "genre_id" => "22"],
            ["movie_id" => "182", "genre_id" => "23"],
            ["movie_id" => "183", "genre_id" => "23"],
            ["movie_id" => "184", "genre_id" => "23"],
            ["movie_id" => "185", "genre_id" => "23"],
            ["movie_id" => "186", "genre_id" => "23"],
            ["movie_id" => "187", "genre_id" => "23"],
            ["movie_id" => "188", "genre_id" => "23"],
            ["movie_id" => "189", "genre_id" => "23"],
            ["movie_id" => "190", "genre_id" => "23"],
            ["movie_id" => "191", "genre_id" => "23"],
            ["movie_id" => "192", "genre_id" => "23"],
            ["movie_id" => "182", "genre_id" => "24"],
            ["movie_id" => "191", "genre_id" => "24"],
            ["movie_id" => "192", "genre_id" => "24"],
            ["movie_id" => "193", "genre_id" => "24"],
            ["movie_id" => "182", "genre_id" => "25"],
            ["movie_id" => "183", "genre_id" => "25"],
            ["movie_id" => "184", "genre_id" => "25"],
            ["movie_id" => "185", "genre_id" => "25"],
            ["movie_id" => "186", "genre_id" => "25"],
            ["movie_id" => "189", "genre_id" => "25"],
            ["movie_id" => "191", "genre_id" => "25"],
            ["movie_id" => "186", "genre_id" => "26"],
            ["movie_id" => "187", "genre_id" => "26"],
            ["movie_id" => "188", "genre_id" => "26"],
            ["movie_id" => "189", "genre_id" => "26"],
            ["movie_id" => "186", "genre_id" => "27"],
            ["movie_id" => "191", "genre_id" => "27"],
            ["movie_id" => "192", "genre_id" => "27"],
            ["movie_id" => "193", "genre_id" => "27"],
            ["movie_id" => "187", "genre_id" => "28"],
            ["movie_id" => "187", "genre_id" => "30"],
            ["movie_id" => "188", "genre_id" => "30"],
            ["movie_id" => "189", "genre_id" => "30"],
            ["movie_id" => "190", "genre_id" => "30"],
            ["movie_id" => "191", "genre_id" => "30"],
            ["movie_id" => "192", "genre_id" => "30"],
            ["movie_id" => "188", "genre_id" => "31"],
            ["movie_id" => "188", "genre_id" => "32"],
            ["movie_id" => "189", "genre_id" => "32"],
            ["movie_id" => "189", "genre_id" => "33"],
            ["movie_id" => "193", "genre_id" => "34"],
            ["movie_id" => "193", "genre_id" => "35"],
            ["movie_id" => "193", "genre_id" => "36"],
            ["movie_id" => "193", "genre_id" => "37"]
        ];
        $stmt_movie_genres = $con->prepare("INSERT IGNORE INTO movie_genres (movie_id, genre_id) VALUES (:movie_id, :genre_id)");
        foreach ($movie_genres_data as $mg_data) {
            $stmt_movie_genres->execute($mg_data);
        }

        $con->exec("
            CREATE TABLE IF NOT EXISTS comments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                movie_id INT DEFAULT NULL,
                user_id INT DEFAULT NULL,
                comment_text TEXT,
                rating INT DEFAULT NULL CHECK (rating >= 1 AND rating <= 10),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $con->exec("
            CREATE TABLE IF NOT EXISTS watchlist (
                user_id INT NOT NULL,
                movie_id INT NOT NULL,
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (user_id, movie_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");

        $con->exec("
           CREATE TABLE IF NOT EXISTS movie_ratings (
               id INT AUTO_INCREMENT PRIMARY KEY,
               movie_id INT NOT NULL,
               user_id INT NOT NULL,
               rating INT NOT NULL CHECK (rating >= 1 AND rating <= 10),
               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
               updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
               UNIQUE KEY unique_user_movie_rating (user_id, movie_id),
               FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
               FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
           ) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci
        ");
    } catch (PDOException $e) {
        echo "Veritabanı Kurulum Hatası: " . $e->getMessage();
    }
}
