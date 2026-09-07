<?php
require_once __DIR__ . '/database.php';

function get_section_content(string $section, array $default): array
{
    try {
        $stmt = db()->prepare("SELECT content FROM site_content WHERE section = :s LIMIT 1");
        $stmt->execute(['s' => $section]);
        $json = $stmt->fetchColumn();
        if ($json) {
            $data = json_decode($json, true);
            if (is_array($data)) {
                return array_merge($default, $data);
            }
        }
    } catch (Exception $e) {}
    return $default;
}

function get_table_items(string $table): array
{
    try {
        return db()->query("SELECT * FROM `$table` ORDER BY sort_order ASC, id ASC")->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

define('SITE', get_section_content('site', [
    'title'       => 'Raphzx Portfolio',
    'tagline'     => 'Junior Software Developer',
    'description' => 'Crafting exceptional web applications with clean code, modern architecture, and pixel-perfect design.',
    'author'      => 'Raphzx',
    'email'       => 'raphwahyudi@gmail.com',
    'location'    => 'Banjarmasin, Indonesia',
    'year'        => date('Y'),
]));

$heroDefault = [
    'greeting'   => '< Junior Software Developer />',
    'title_1'    => 'I Build',
    'title_2'    => 'Digital',
    'title_3'    => 'Experiences',
    'subtitle'   => 'Building functional and responsive web applications with clean code.',
    'cta_1_text' => 'View Projects',
    'cta_1_href' => '#projects',
    'cta_2_text' => 'Contact Me',
    'cta_2_href' => '#contact',
];
$heroDb = get_section_content('hero', $heroDefault);
define('HERO', [
    'greeting' => $heroDb['greeting'],
    'title_1'  => $heroDb['title_1'],
    'title_2'  => $heroDb['title_2'],
    'title_3'  => $heroDb['title_3'],
    'subtitle' => $heroDb['subtitle'],
    'cta_1'    => ['text' => $heroDb['cta_1_text'], 'href' => $heroDb['cta_1_href']],
    'cta_2'    => ['text' => $heroDb['cta_2_text'], 'href' => $heroDb['cta_2_href']],
]);

$aboutDefaults = [
    'tag'         => 'Get To Know Me',
    'heading'     => 'About Me',
    'number'      => '01',
    'title'       => 'The Developer',
    'paragraph_1' => 'Vocational high school students at SMK ISFI Banjarmasin who are focused and dedicated to full-stack web development.',
    'paragraph_2' => 'Actively developing various school-based web projects and management systems.',
];
$aboutDb = get_section_content('about', $aboutDefaults);

$statsItems = get_table_items('about_stats');
$statsArr = [];
foreach ($statsItems as $s) {
    $statsArr[] = ['value' => $s['value'], 'label' => $s['label']];
}
if (empty($statsArr)) {
    $statsArr = [
        ['value' => '3+', 'label' => 'Years Exp'],
        ['value' => '3',  'label' => 'Projects'],
        ['value' => '0',  'label' => 'Clients'],
    ];
}

$featuresItems = get_table_items('about_features');
$featuresArr = [];
foreach ($featuresItems as $f) {
    $featuresArr[] = ['icon_code' => $f['icon_code'], 'title' => $f['title'], 'desc' => $f['description']];
}
if (empty($featuresArr)) {
    $featuresArr = [
        ['icon_code' => 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5', 'title' => 'Clean Code', 'desc' => 'Writing structured, readable, and easy-to-maintain code.'],
        ['icon_code' => 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42', 'title' => 'UI/UX Design', 'desc' => 'Designing clean, responsive, and user-friendly interfaces.'],
        ['icon_code' => 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z', 'title' => 'Performance', 'desc' => 'Ensuring fast loading times and smooth overall web application performance.'],
    ];
}

define('ABOUT', [
    'tag'         => $aboutDb['tag'],
    'heading'     => $aboutDb['heading'],
    'number'      => $aboutDb['number'],
    'title'       => $aboutDb['title'],
    'paragraph_1' => $aboutDb['paragraph_1'],
    'paragraph_2' => $aboutDb['paragraph_2'],
    'stats'       => $statsArr,
    'features'    => $featuresArr,
]);

$skillsItems = get_table_items('skills');
$skillsArr = [];
foreach ($skillsItems as $sk) {
    $skillsArr[] = ['name' => $sk['name'], 'level' => (int)$sk['level'], 'icon' => $sk['icon']];
}
if (empty($skillsArr)) {
    $skillsArr = [
        ['name' => 'HTML5 / CSS3', 'level' => 98, 'icon' => 'assets/logo/html-css3.png'],
    ];
}

define('SKILLS', [
    'tag'     => 'My Arsenal',
    'heading' => 'Technical Skills',
    'items'   => $skillsArr,
]);

$contactDb = get_section_content('contact', [
    'tag'       => 'Get In Touch',
    'heading'   => 'Contact Me',
    'subtitle'  => 'Have a question or want to discuss a project? Drop a message below.',
]);

$socialsItems = get_table_items('contact_socials');
$socialsArr = [];
foreach ($socialsItems as $sc) {
    $socialsArr[] = ['platform' => $sc['platform'], 'url' => $sc['url'], 'path' => $sc['path']];
}

define('CONTACT', [
    'tag'       => $contactDb['tag'],
    'heading'   => $contactDb['heading'],
    'subtitle'  => $contactDb['subtitle'],
    'form' => [
        'name'    => ['label' => 'Name',    'placeholder' => 'Your Name',        'type' => 'text'],
        'email'   => ['label' => 'Email',   'placeholder' => 'mail@example.com',  'type' => 'email'],
        'subject' => ['label' => 'Subject', 'placeholder' => 'Project Inquiry',   'type' => 'text'],
        'message' => ['label' => 'Message', 'placeholder' => 'Tell me about your project...', 'type' => 'textarea'],
    ],
    'socials' => $socialsArr,
]);
