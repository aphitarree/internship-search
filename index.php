<?php
require_once __DIR__ . '/config/db_config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Pagination variables
$page = (int)($_GET['page'] ?? 1);
$pageSize = 10;
$offset = ($page - 1) * $pageSize;

// Filter parameters
$faculty = $_GET['faculty'] ?? null;
$program = $_GET['program'] ?? null;
$major = $_GET['major'] ?? null;
$province = $_GET['province'] ?? null;
$academicYear = $_GET['academic-year'] ?? null;

// Build the WHERE clause
$whereClause = [];
$params = [];
if ($faculty) {
    $whereClause[] = 'fpm.faculty = :faculty';
    $params[':faculty'] = htmlspecialchars($faculty);
}
if ($program) {
    $whereClause[] = 'fpm.program = :program';
    $params[':program'] = htmlspecialchars($program);
}
if ($major) {
    $whereClause[] = 'fpm.major = :major';
    $params[':major'] = htmlspecialchars($major);
}
if ($province) {
    $whereClause[] = 'stats.province = :province';
    $params[':province'] = htmlspecialchars($province);
}
if ($academicYear) {
    $whereClause[] = 'stats.year = :academic_year';
    $params[':academic_year'] = htmlspecialchars($academicYear);
    echo $academicYear;
}

$whereSql = '';
if (!empty($whereClause)) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereClause);
}

// Get total number of records
$countSql = "
    SELECT COUNT(*) FROM internship_stats AS stats
    LEFT JOIN faculty_program_major AS fpm ON stats.major_id = fpm.id
    $whereSql
";

$stmtCount = $conn->prepare($countSql);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $pageSize);

// Fetch records for the current page
$sql = "
    SELECT
        stats.id,
        stats.organization AS company_name,
        stats.province,
        stats.position AS job_title,
        fpm.faculty AS faculty_name,
        fpm.program AS program_name,
        fpm.major AS major_name,
        stats.year AS academic_year,
        stats.total_student AS internship_count
    FROM internship_stats stats
    LEFT JOIN faculty_program_major fpm ON stats.major_id = fpm.id
    $whereSql
    ORDER BY stats.id DESC
    LIMIT :pageSize OFFSET :offset";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':pageSize', $pageSize, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build base URL for pagination
$baseUrl = $_ENV['BASE_URL'];
$query_params = [];
parse_str($_SERVER['QUERY_STRING'] ?? '', $query_params);
unset($query_params['page']);
$baseUrl .= '?' . http_build_query($query_params);
?>

<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="icon" type="image/png" href="public/images/favicon-32x32.png">

    <title>คลังประวัติการฝึกงาน</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Choices.js for creating searchable dropdown element -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="css/globals.css">
</head>

<body class="bg-white text-gray-900">
    <!-- Navigator bar -->
    <?php include_once './components/navbar.php'; ?>

    <!-- Banner -->
    <section class="relative w-full">
        <img src="public/images/background-1.jpg" alt="Banner" class="w-full h-[185px] object-cover" />
        <div class="absolute inset-0 bg-black/25"></div>
        <h1 class="absolute inset-0 flex items-center justify-center text-white text-2xl md:text-3xl font-semibold text-center">
            คลังประวัติการฝึกงานของมหาวิทยาลัยสวนดุสิต
        </h1>
    </section>

    <div class="md:container md:mx-auto">
        <main class="mx-auto w-full max-w-[1900px] px-4 py-2 mt-4 grid grid-cols-1 gap-6 lg:grid-cols-[2fr,1fr] lg:gap-8">
            <?php include_once './components/chart.php' ?>
            <?php include_once './components/access_stats.php' ?>
        </main>

        <!-- Filters -->
        <?php include_once './components/filter_search.php' ?>

        <!-- Search result table -->
        <?php include_once './components/result_table.php' ?>

        <!-- Pagination -->
        <?php include_once './components/pagination.php' ?>

        <!-- Download report -->
        <?php include_once './components/report.php' ?>
    </div>

    <!-- Footer bar -->
    <?php include_once './components/footer.php'; ?>
</body>

</html>