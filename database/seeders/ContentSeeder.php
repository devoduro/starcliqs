<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Training;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Starter website content, carried over from the original static homepage. Each table is only
 * filled while it is empty, so running the seeder again never overwrites edits made in the admin.
 * Trainings and blog posts are sample entries to be replaced with real ones.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->settings();
        $this->services();
        $this->products();
        $this->schoolSystems();
        $this->projects();
        $this->laterProjects();
        $this->testimonials();
        $this->trainings();
        $this->posts();
        $this->faqs();
        $this->clients();
    }

    /**
     * Client logos for the homepage. Added by name, so new clients appear on existing sites without
     * touching ones already edited. Logo files ship in database/seeders/assets/clients.
     */
    private function clients(): void
    {
        $clients = [
            ['Prempeh College', 'prempeh-college.webp', 'https://prempehcollege.edu.gh'],
            ['Fening School', 'fening-school.png', null],
            ["St. Monique's Montessori School", 'st-moniques-montessori.jpg', 'http://stmoniquesmontessori.edu.gh/'],
            ['Metro Health Hospital', 'metro-health-hospital.png', 'https://metrohealthgh.com/'],
            ['Otisam', 'otisam.svg', null],
            ['St. Louis College of Education', 'st-louis-college-of-education.png', 'https://slce.edu.gh'],
            ['PCG Peyer Memorial Congregation, Bantama', 'pcg-peyer-memorial.png', null],
            ['AAMUSTED', 'aamusted.png', 'https://www.aamusted.edu.gh'],
            ['Simms Senior High School', 'simms-shs.jpg', null],
            ['GhCodeHub', 'ghcodehub.png', 'https://ghcodehub.com'],
            ['Drezwell, Accra', 'drezwell.jpg', null],
            ['Naky Publications', 'naky-publications.jpg', null],
            ['Yaf Ghana', 'yaf-ghana.png', 'https://yafghana.org/'],
            ['Ashlocs, UK', 'ashlocs.png', 'https://ashlocs.co.uk/'],
            ['Kids Palace School', 'kids-palace-school.png', 'https://kidspalaceschool.com/'],
            ['SigmaCorp Ghana', 'sigmacorp.jpg', 'https://sigmacorpgh.com/'],
            ['Niragira Community Development Project (NCDP)', 'ncdp.png', 'https://niragiracommunitydevelopment.org/'],
            ['Quayson College Consulting', 'quayson-college-consulting.png', 'https://quaysoncollegeconsulting.com/'],
        ];

        foreach ($clients as $i => [$name, $logo, $url]) {
            if (Client::where('name', $name)->exists()) {
                continue;
            }
            $path = null;
            if ($logo && is_file($source = database_path('seeders/assets/clients/' . $logo))) {
                $path = 'clients/' . $logo;
                Storage::disk('uploads')->put($path, file_get_contents($source));
            }
            Client::create(['name' => $name, 'logo' => $path, 'website_url' => $url, 'sort_order' => $i + 1]);
        }

        // Clients that use one of our own products link to its page.
        $otisam = Product::where('slug', 'otisam')->value('id');
        if ($otisam) {
            Client::where('name', 'Otisam')->whereNull('product_id')->update(['product_id' => $otisam]);
        }
    }

    /**
     * The school management systems StarCliqs has built and runs today. Added by slug, so they are
     * created on existing sites too, but an edited or deleted entry is never re-created.
     */
    private function schoolSystems(): void
    {
        $systems = [
            [
                'slug' => 'basic-school-management-system',
                'name' => 'Basic School Management System',
                'tagline' => 'For nursery, kindergarten and primary schools',
                'icon' => 'fa-children',
                'summary' => 'Runs the whole basic school — pupil records, fees, continuous assessment and terminal reports — with portals for teachers, pupils and parents.',
                'features' => [
                    'Pupil records, class lists and a searchable pupil directory',
                    'Fee structures, bulk billing, payments and arrears tracking',
                    'Continuous assessment and score sheets for every subject',
                    'Terminal report cards and transcripts, ready to print',
                    'End-of-year promotions in a few clicks',
                    'Timetables and a teacher portal',
                    'Online quizzes and assignments for pupils',
                    'Parent notices by SMS and email',
                    'Staff roles, permissions and an activity log',
                ],
                'body' => "Built for Ghanaian basic schools, this system keeps everything about a pupil in one place: personal details, class, fees owed and paid, scores and reports.\n\n## For the school office\n- Set fee structures per class and bill everyone at once\n- Record payments and see arrears instantly\n- Print terminal reports and transcripts in the school's own letterhead\n\n## For teachers\n- Enter continuous assessment and exam scores per subject\n- Set online quizzes and assignments\n- See their own classes and timetable in the teacher portal\n\n## For parents\nParents receive fee reminders, results and school notices by SMS and email, so nobody misses an update.",
            ],
            [
                'slug' => 'jhs-management-system',
                'name' => 'JHS Management System',
                'tagline' => 'Junior High School administration, end to end',
                'icon' => 'fa-school',
                'summary' => 'Admissions, attendance, assessment, fees and reports for Junior High Schools — including biometric attendance and boarding management.',
                'features' => [
                    'Online admissions with an applicant portal',
                    'Biometric campus and lesson attendance',
                    'Continuous assessment, exams and terminal reports',
                    'Fees, payment uploads and arrears',
                    'Houses, boarding status and exeat management',
                    'Timetables, venues and teacher portal',
                    'Online quizzes and assignments',
                    'Bulk SMS to parents and staff',
                    'Transcripts and promotions',
                ],
                'body' => "Designed around how Junior High Schools in Ghana actually run, from admission through to the final transcript.\n\n## Admissions to graduation\n- Applicants apply online and the school processes them in one place\n- Students are placed into classes and houses, with boarding status recorded\n- Promotions and transcripts are generated from the scores already in the system\n\n## Attendance you can trust\nBiometric devices record campus and lesson attendance automatically, and exeats are tracked for boarders.\n\n## Results and reports\nTeachers enter continuous assessment and exam scores; the system grades them and produces terminal reports ready for parents.",
            ],
            [
                'slug' => 'college-aims',
                'name' => 'College AIMS',
                'tagline' => 'Academic Information Management System for tertiary institutions',
                'icon' => 'fa-building-columns',
                'summary' => 'A complete academic and student management system for colleges — admissions, course registration, GPA results, fees, LMS, lecturer evaluation and STS placement.',
                'features' => [
                    'Online admissions, applicant portal and admission letters',
                    'Semester course registration',
                    'Continuous assessment, results, GPA and resits',
                    'Transcripts and graduation lists',
                    'Fee billing, payments and arrears',
                    'Halls of residence and biometric attendance',
                    'Learning management: course materials, assignments and quizzes',
                    'Lecturer portal and student evaluation of lecturers',
                    'STS placement, supervision and scoring with partner schools',
                    'Timetables, venues and bulk SMS',
                ],
                'body' => "College AIMS runs the academic life of a tertiary institution, from the first application to graduation. It is in use at St. Louis College of Education.\n\n## Students\n- Apply, receive admission letters and register for courses online\n- See results, GPA and fees in their own portal\n- Access course materials, assignments and quizzes in the built-in LMS\n\n## Lecturers and academic office\n- Lecturers manage their courses, enter scores and teach through the LMS\n- The academic office handles resits, GPA distribution, transcripts and graduation\n- Students evaluate lecturers each semester, with reports for management\n\n## Supported Teaching in Schools (STS)\nPlace student teachers in partner schools, manage quotas, record supervision visits and capture STS scores.",
            ],
        ];

        foreach ($systems as $system) {
            Product::firstOrCreate(['slug' => $system['slug']], $system + ['is_featured' => true, 'sort_order' => 0, 'is_published' => true]);
        }

        Product::firstOrCreate(['slug' => 'otisam'], [
            'name' => 'Otisam',
            'tagline' => 'Learn it. Practise it. Pass it.',
            'icon' => 'fa-book-open-reader',
            'summary' => 'An online learning and teaching platform for Ghanaian students and teachers: WASSCE past questions with worked solutions, curriculum-mapped notes, video lessons and teacher resources, built to work on any phone.',
            'features' => [
                'WASSCE past questions with worked solutions',
                'Notes mapped to the GES/NaCCA curriculum',
                'Practice by topic, quick practice and practice papers',
                'Video lessons and a downloadable resource library',
                "Chief Examiners' reports",
                'Q&A community and leaderboard',
                'Teacher classrooms, class lists, assignments and materials',
                'Lesson plans and question writing for teachers',
                'Affordable premium plans',
            ],
            'body' => "Otisam is an online student-teacher system for Ghana. Students learn from the current curriculum, practise with instant feedback and prepare for WASSCE; teachers get lesson materials, classrooms and professional tools. Everything is designed to work on an inexpensive phone over mobile data.\n\n## For students\n- Study by subject and class, with notes mapped to the GES/NaCCA curriculum\n- Practise WASSCE past questions with worked solutions, by topic or as full papers\n- Watch video lessons and download resources from the library\n- Ask and answer questions in the Q&A community and track progress on the leaderboard\n\n## For teachers\n- Register and, once verified, unlock teacher resources\n- Run classrooms: class lists, assignments, materials and video lessons\n- Use lesson plans and question-writing tools\n- Teacher promotion and professional practice exams are on the way\n\n## Plans\nCore study tools are free. Premium plans unlock the full library at an affordable price.",
            'is_featured' => true,
            'sort_order' => 0,
            'is_published' => true,
        ]);

        Product::firstOrCreate(['slug' => 'clickvote'], [
            'name' => 'ClickVote',
            'tagline' => 'Awards voting, nominations & events made simple',
            'icon' => 'fa-square-poll-vertical',
            'summary' => "Ghana's platform for award voting, nominations and events. Voters cast secure votes on the web or by USSD and pay with Mobile Money; organisers track votes and revenue live.",
            'features' => [
                'Vote on the web or by USSD (*920*134#) on any network',
                'Mobile Money payments: MTN MoMo, Telecel Cash, AirtelTigo Money',
                'Votes confirmed instantly, with a PDF receipt and reference',
                'Organisers manage their own categories and nominees',
                'Live vote and revenue dashboards',
                'Payment reconciliation, fraud and audit monitoring',
                'Publish live results, hide counts, or use an anonymous tally',
                'CSV and PDF exports',
                'Sponsorship and fundraising campaigns',
            ],
            'body' => "ClickVote runs awards, nominations, elections and events across Ghana. Voters choose a nominee, decide how many votes to buy and approve the Mobile Money prompt on their phone. Their PIN stays with their network, and every vote is recorded only once the payment is confirmed.\n\n## For voters\n- Browse live awards and search nominees by name or code\n- Vote on the web, or dial *920*134# on any network\n- Get an instant confirmation and a downloadable PDF receipt\n\n## For organisers\n- Set up categories and nominees and choose the price per vote\n- Watch votes and revenue live, with reconciliation and audit tools\n- Publish results when ready, keep counts hidden, or run an anonymous tally\n- Export everything to CSV or PDF\n\n## Sponsorships\nRun fundraisers and sponsorship drives where supporters simply give, without voting.",
            'demo_url' => 'https://clickvotegh.com/',
            'is_featured' => true,
            'sort_order' => 0,
            'is_published' => true,
        ]);
    }

    private function faqs(): void
    {
        if (Faq::exists()) {
            return;
        }

        $rows = [
            ['Custom Software', 'fa-code', 'Do you build from scratch?', 'Yes, we engineer tailored software solutions designed specifically for your unique business processes and goals.'],
            ['School Management', 'fa-graduation-cap', 'What does DigiSchool cover?', 'It handles admissions, grading, finance, student records, and portals for staff and parents in one secure system.'],
            ['Church Administration', 'fa-church', 'Is ChurchCliq easy to use?', 'Absolutely. Manage membership databases, tithes, donations, and communication with an intuitive, user-friendly interface.'],
            ['Bulk Messaging', 'fa-comment-sms', 'Can I schedule messages?', 'Yes, SmartBulk allows you to schedule campaigns, personalise messages, and track delivery reports in real time.'],
            ['E-Voting Solutions', 'fa-check-to-slot', 'Is it secure?', 'DigiBallot guarantees transparency and security for elections, providing instant results and audit trails for verified fairness.'],
            ['General Support', 'fa-headset', 'Do you offer maintenance?', 'We provide ongoing technical support, system maintenance, and IT consultancy to keep your operations running smoothly.'],
        ];

        foreach ($rows as $i => [$topic, $icon, $question, $answer]) {
            Faq::create(['topic' => $topic, 'icon' => $icon, 'question' => $question, 'answer' => $answer, 'sort_order' => $i + 1]);
        }
    }

    private function settings(): void
    {
        $defaults = [
            'site_name' => 'StarCliqs',
            'tagline' => 'IT consultancy & digital solutions',
            'meta_description' => 'StarCliqs delivers reliable, scalable and user-friendly IT systems for schools, churches, businesses and government across Ghana.',
            'founded_year' => '2013',
            'services_intro' => "We provide a wide range of software services to meet our clients' business needs.",
            'academy_title' => 'Learn tech by',
            'academy_highlight' => 'building',
            'academy_title_end' => 'real products.',
            'academy_subtitle' => 'Hands-on programmes taught by the engineers who build and support StarCliqs systems. Graduate with a portfolio of real work, not just a certificate.',
            'academy_onsite_location' => 'Kumasi',
            'academy_onsite_text' => 'Learn at our Kumasi office with lab computers, reliable internet and direct access to instructors and classmates.',
            'academy_onsite_points' => implode("\n", ['Hands-on lab sessions', 'Small classes with in-person mentorship', 'Work alongside our engineering team']),
            'academy_online_text' => 'The same curriculum from anywhere: live online classes with recordings, so you never miss a lesson.',
            'academy_online_points' => implode("\n", ['Live Zoom / Google Meet sessions', 'Recordings of every class', 'Class WhatsApp group with instructors', 'Same projects and certificate']),
            'academy_perks' => implode("\n", [
                'Live classes | Small groups and real-time feedback, not pre-recorded videos.',
                'Real projects | Build features modelled on the systems we deliver to clients.',
                'Mentorship | Learn from working engineers who still ship code.',
                'Certificate | Earn a StarCliqs certificate when you complete your programme.',
            ]),
            'rate_limit_per_minute' => '120',
            'form_limit_per_hour' => '10',
            'visit_log_enabled' => '1',
            'visit_log_days' => '90',
            'dpc_show' => '1',
            'dpc_url' => 'https://www.dataprotection.org.gh',
            'phone' => '0242724849',
            'whatsapp' => '233200346659',
            'email' => 'info@starcliqs.com',
            'address' => 'Kumasi, Ghana',
            'office_hours' => 'Mon – Fri, 8:00am – 5:00pm',
            'hero_eyebrow' => '',
            'hero_title' => 'Empowering Ghana with',
            'hero_highlight' => 'Digital Innovation',
            'hero_subtitle' => 'Reliable, Scalable, and User-Friendly IT Systems tailored for your success.',
            'about_title' => 'Decade of Excellence in IT Solutions',
            'about_body' => 'With over a decade of experience and more than 150 successful projects, StarCliqs is a trusted partner in delivering customised IT solutions across Ghana. We design, build and support systems that schools, churches, hospitals, businesses and public institutions rely on every day.',
            'about_highlights' => implode("\n", ['Certified IT professionals', 'Custom-tailored strategies', 'Nationwide support', 'Cutting-edge technology']),
            'stat_years' => '10',
            'stat_projects' => '150',
            'stat_clients' => '80',
            'stat_success' => '98',
            'partners' => implode("\n", [
                'Metro Hospital – Kumasi', 'AAMUSTED (DigiMakt Project)', 'Drezwell – Accra', 'Prempeh College', 'St. Louis College of Education',
                'Naky Publications', 'Simms SHS', 'Yaf Ghana',
            ]),
        ];

        $existing = Setting::pluck('key')->all();
        Setting::put(array_diff_key($defaults, array_flip($existing)));
    }

    private function services(): void
    {
        if (Service::exists()) {
            return;
        }

        $rows = [
            ...self::SOFTWARE_SERVICES,
            ['Database Management', 'fa-database', 'Secure and efficient data storage solutions that keep your records safe and fast to find.',
                ['Database design and migration', 'Automated backups and recovery', 'Performance tuning', 'Data security and access control']],
            ['Network Administration', 'fa-network-wired', 'Robust network infrastructure setup and maintenance for offices, campuses and hospitals.',
                ['Structured cabling and Wi-Fi', 'Server room setup', 'Firewall and security', 'Ongoing monitoring and support']],
            ['CCTV Installation', 'fa-video', 'Advanced security surveillance systems with remote viewing on your phone.',
                ['HD and IP camera systems', 'Remote mobile viewing', 'Night vision and motion alerts', 'Maintenance contracts']],
            ['Bulk SMS Campaigns', 'fa-comment-sms', 'Targeted messaging for effective communication with parents, members and customers.',
                ['Custom sender IDs', 'Scheduled and personalised messages', 'Instant delivery reports', 'API for your own systems']],
            ['IT Consultancy', 'fa-lightbulb', 'Expert guidance on digital transformation, procurement and IT strategy.',
                ['IT audits and strategy', 'System selection and procurement', 'Staff training', 'Project management']],
        ];

        foreach ($rows as $i => $row) {
            [$title, $icon, $summary, $features] = $row;
            Service::create([
                'title' => $title, 'icon' => $icon, 'summary' => $summary, 'features' => $features, 'sort_order' => $i + 1,
                'body' => $row[4] ?? $summary . "\n\nOur team works with you from the first conversation through to launch and long-term support. We take time to understand how your organisation works, recommend what fits your budget, and stay available after handover.",
            ]);
        }
    }

    /** The software development services: [title, icon, summary, features, body]. */
    public const SOFTWARE_SERVICES = [
        ['Desktop Applications', 'fa-desktop',
            'We build cross-platform desktop applications. Our professional approach keeps your application extensible and easy to test, with a modern, user-friendly design your team will quickly get used to.',
            ['Windows, macOS and Linux from one codebase', 'Works offline, syncs when online', 'Modern, user-friendly interfaces', 'Extensible, well-tested code'],
            "We build cross-platform desktop applications that run on Windows, macOS and Linux from a single codebase.\n\nOur professional approach to software development keeps your application extensible and easy to test, so it can grow with your business. We always aim for modern, user-friendly design, so your staff get comfortable with the new application quickly.\n\n## What we deliver\n- Point-of-sale, inventory, records and back-office systems\n- Apps that keep working offline and sync when the connection returns\n- Installers, automatic updates and staff training\n- Ongoing support and maintenance"],
        ['Web Applications', 'fa-laptop-code',
            'As a software development company, we aim to offer top-quality web development solutions. Our web solutions give our clients a competitive edge.',
            ['Business portals and management systems', 'Company websites and e-commerce', 'Online payments and SMS integration', 'Secure hosting and maintenance'],
            "As a software development company, we always aim to offer top-quality web development solutions. Through our web solutions, our clients enjoy a competitive edge.\n\n## What we build\n- Management systems and portals for schools, churches, hospitals and businesses\n- Company websites, online shops and booking systems\n- Integrations with mobile money, card payments and bulk SMS\n\n## How we work\nWe design for speed, security and phones first, then host, monitor and maintain the application after launch."],
        ['Mobile Applications', 'fa-mobile-screen-button',
            'Apps designed to run on phones, tablets and watches. Unlike web apps that run in a browser, mobile apps run directly on the device for a faster, richer experience.',
            ['Android and iOS apps', 'Push notifications', 'Offline access and device features', 'Play Store and App Store publishing'],
            "A mobile application is software designed to run on a mobile device such as a phone, tablet or watch. Mobile apps differ from desktop apps, which run on desktop computers, and from web apps, which run in a mobile web browser rather than directly on the device.\n\n## What we deliver\n- Android and iOS apps from one codebase\n- Push notifications, camera, location and offline storage\n- Apps that connect to your existing systems and payments\n- Publishing to the Play Store and App Store, plus updates"],
        ['USSD Applications', 'fa-hashtag',
            'USSD lets customers use your service by dialling a short code on any phone — no internet or smartphone needed. Ideal for payments, balance checks, registrations and voting.',
            ['Works on every mobile phone', 'No internet or data needed', 'Payments, balances and registrations', 'Integration with mobile networks and your systems'],
            "USSD (Unstructured Supplementary Service Data) is a protocol of the Global System for Mobile Communications (GSM) used to send text between a phone and an application. Like SMS, it works on any mobile phone, using codes made up of the characters available on the phone, such as *123#.\n\nBecause USSD needs no internet connection or smartphone, it reaches every customer.\n\n## Common uses\n- Payments and mobile money services\n- Balance, results and status checks\n- Registrations, surveys and voting\n- Menus that connect to your existing system"],
    ];

    private function products(): void
    {
        if (Product::exists()) {
            return;
        }

        $rows = [
            ['DigiSchool', 'School management, simplified', 'fa-school', true,
                'A comprehensive school management ecosystem tailored for Ghanaian institutions. Manages students, fees, results, attendance and parent communication in one place.',
                ['Admissions and student records', 'Fees billing and payment tracking', 'Continuous assessment and terminal reports', 'Timetables and attendance', 'Parent SMS and email alerts', 'Teacher and pupil portals']],
            ['SmartBulk SMS', 'Reach everyone, instantly', 'fa-comment-sms', false,
                'High-performance bulk messaging platform with instant delivery reports. Ideal for schools, churches and businesses.',
                ['Custom sender ID', 'Contact groups and imports', 'Scheduled messages', 'Delivery reports', 'Developer API']],
            ['ChurchCliq', 'Church administration made easy', 'fa-church', false,
                'Modern administration tool for religious organisations. Track membership, tithes, donations, events and communication.',
                ['Membership database', 'Tithes and offering records', 'Event and attendance tracking', 'Financial reports', 'Bulk SMS to members']],
            ['DigiBallot', 'Secure electronic voting', 'fa-check-to-slot', false,
                'Secure, transparent and user-friendly electronic voting system. Perfect for school, association and organisational elections.',
                ['Verified voter access', 'Live results dashboard', 'Tamper-proof audit trail', 'Works on any phone', 'Instant result sheets']],
            ['DocBox', 'Your archive, digitised', 'fa-box-archive', false,
                'Digital archiving solution. Digitise, store and retrieve physical documents with powerful search.',
                ['Scanning and indexing', 'Full-text search', 'Role-based access', 'Secure cloud backup']],
            ['SHSAdmissions', 'Admissions without the queues', 'fa-user-graduate', false,
                'Streamlined admission portal for Senior High Schools. Automates placement verification, forms, payments and prospectus downloads.',
                ['Placement verification', 'Online admission forms', 'Mobile money payments', 'Prospectus and letters', 'Admin dashboard']],
        ];

        foreach ($rows as $i => [$name, $tagline, $icon, $featured, $summary, $features]) {
            Product::create([
                'name' => $name, 'tagline' => $tagline, 'icon' => $icon, 'is_featured' => $featured, 'summary' => $summary,
                'features' => $features, 'sort_order' => $i + 1,
                'body' => $summary . "\n\nEvery installation includes setup, data migration from your existing records, staff training and ongoing support from the StarCliqs team.",
            ]);
        }
    }

    private function projects(): void
    {
        if (Project::exists()) {
            return;
        }

        $rows = [
            ['DigiSchool rollout for Prempeh College', 'Prempeh College', 'Education', true, '2024-09-01',
                'Moved administration, fees and terminal reports onto DigiSchool, saving staff hours of paperwork every term.',
                ['DigiSchool', 'Bulk SMS', 'Staff training']],
            self::WELFARE_PROJECT,
            ['Hospital network modernisation', 'Metro Hospital – Kumasi', 'Networking', true, '2023-11-20',
                'Redesigned the hospital network and server room, improving the speed of service delivery across departments.',
                ['Structured cabling', 'Wi-Fi', 'Firewall', 'Server room']],
            ['DigiMakt marketplace platform', 'AAMUSTED', 'Software', false, '2023-06-30',
                'Built the DigiMakt platform connecting student entrepreneurs with buyers.',
                ['Web application', 'Payments', 'Admin dashboard']],
            ['College of Education portal', 'St. Louis College of Education', 'Education', false, '2025-01-10',
                'Student records, results and fees portal for a college of education.',
                ['Student portal', 'Results', 'Fees']],
            ['Pharmacy CCTV and network', 'Humble Mother Pharmacy', 'Security', false, '2023-02-14',
                'HD CCTV coverage with remote viewing, plus a reliable shop network.',
                ['CCTV', 'Remote viewing', 'Networking']],
        ];

        foreach ($rows as $row) {
            [$title, $client, $category, $featured, $date, $summary, $tech] = $row;
            Project::create([
                'title' => $title, 'client' => $client, 'category' => $category, 'is_featured' => $featured, 'completed_on' => $date,
                'summary' => $summary, 'technologies' => $tech,
                'body' => $row[7] ?? $summary . "\n\nUpdate this project from the admin panel with the full story: the challenge, what was delivered, and the results.",
            ]);
        }
    }

    /** Projects added after the first install: created once by slug, never overwritten. */
    private function laterProjects(): void
    {
        foreach ([self::POS_PROJECT] as [$title, $client, $category, $featured, $date, $summary, $tech, $body]) {
            Project::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($title)], [
                'title' => $title, 'client' => $client, 'category' => $category, 'is_featured' => $featured, 'completed_on' => $date,
                'summary' => $summary, 'technologies' => $tech, 'body' => $body,
            ]);
        }
    }

    public const POS_PROJECT = [
        'Sales & POS System for Naky Publications', 'Naky Publications', 'Retail & POS', true, '2024-10-04',
        'A point-of-sale and stock management system for Naky Publications: fast checkout, stock across locations, purchases, customer accounts and sales reports in one place.',
        ['Point of sale', 'Inventory & stock control', 'Purchases & suppliers', 'Accounting & reports', 'Sales reps & zones', 'Barcode labels'],
        "Naky Publications needed one system to sell books, track stock and see how the business is doing. We set up, customised and support a full sales and point-of-sale system, branded as the HiCliQ Ultimate Sales Software and in daily use since October 2024.\n\n## Selling\n- Fast point-of-sale checkout with receipts and custom invoice layouts\n- Sales orders, sales returns and discounts\n- Customer accounts, customer groups and selling-price groups\n- Sales representatives and sales zones, with commission tracking\n\n## Stock\n- Products with brands, units, variations and barcode labels\n- Stock across business locations, with transfers and adjustments\n- Opening stock and product imports from spreadsheets\n\n## Purchasing\n- Purchase orders, requisitions and purchase returns\n- Supplier accounts and payments\n\n## Money and reports\n- Expenses, payment accounts and accounting\n- Cash registers for each till\n- Sales, stock, profit and expense reports, plus a configurable dashboard\n\n## Administration\n- Staff users with roles and permissions\n- Notification templates and database backups",
    ];

    /** [title, client, category, featured, completed on, summary, technologies, body] */
    public const WELFARE_PROJECT = [
        'Association & Welfare Management System', null, 'Associations', true, null,
        'Membership, dues and welfare management for associations and teachers\' welfare groups — with member portals, online payments, biometric attendance and bulk SMS.',
        ['Membership & dues', 'Welfare payments', 'Online payments', 'Biometric attendance', 'Bulk SMS & WhatsApp', 'Member portal'],
        "A complete system for running an association or welfare scheme, built around members and their contributions. It is in use as a Teachers Welfare Management System: \"Supporting Teachers, Strengthening Community\".\n\n## Members\n- Online registration, membership forms and member profiles\n- Year groups to organise members by cohort\n- A member portal to see dues, payments and announcements\n\n## Dues and welfare\n- Dues plans and welfare dues charged to every member\n- Payments allocated to what each member owes, with online payment support\n- Donations, finance records and weekly income returns\n\n## Events and attendance\n- Events and programmes with registration and QR-code check-in\n- Biometric attendance using ZKTeco fingerprint devices\n- SMS reminders before events\n\n## Communication\n- Bulk SMS, WhatsApp and email to all or selected members\n- Automatic birthday messages and announcements\n\n## Administration\n- Staff accounts with roles and permissions, and a full activity log\n- Document library and inventory records",
    ];

    private function testimonials(): void
    {
        if (Testimonial::exists()) {
            return;
        }

        $rows = [
            ['Principal', 'Principal', 'Prempeh College', 5, 'StarCliqs transformed our administrative processes with DigiSchool. The system is intuitive, secure, and has saved us countless hours of paperwork. We can\'t imagine running the school without it now.'],
            ['IT Director', 'IT Director', 'Kumasi Metropolitan Assembly (KMA)', 5, 'The e-voting solution for our elections was flawless. DigiBallot provided transparency and instant results, eliminating all disputes. Highly recommended for any institution.'],
            ['Administrator', 'Administrator', 'Metro Hospital, Kumasi', 5, 'Reliable, professional, and always available support. StarCliqs helped us modernise our hospital\'s network infrastructure, significantly improving our service delivery speed.'],
        ];

        foreach ($rows as $i => [$name, $role, $company, $rating, $quote]) {
            Testimonial::create(['name' => $name, 'role' => $role, 'company' => $company, 'rating' => $rating, 'quote' => $quote, 'sort_order' => $i + 1]);
        }
    }

    private function trainings(): void
    {
        foreach (self::SAMPLE_PROGRAMMES as $i => [$title, $icon, $level, $summary, $duration, $schedule, $mode, $fee, $days, $outline]) {
            $slug = \Illuminate\Support\Str::slug($title);
            if (Training::where('slug', $slug)->exists() || (Training::exists() && $i < 3)) {
                continue;
            }
            Training::create([
                'title' => $title, 'icon' => $icon, 'level' => $level, 'summary' => $summary, 'duration' => $duration, 'schedule' => $schedule,
                'mode' => $mode, 'fee' => $fee, 'starts_on' => now()->addDays($days)->toDateString(),
                'registration_closes_on' => now()->addDays($days - 7)->toDateString(), 'outline' => $outline, 'sort_order' => $i + 1,
                'is_published' => true,
                'body' => $summary . "\n\nSample programme — edit the dates, fee and outline in the admin panel before publishing.",
            ]);
        }
    }

    /** [title, icon, level, summary, duration, schedule, mode, fee, starts in days, outline] */
    public const SAMPLE_PROGRAMMES = [
        ['Web Development Bootcamp', 'fa-code', 'Beginner → Intermediate', 'Build and launch real websites and web apps with HTML, CSS, JavaScript, PHP and MySQL.', '8 weeks', 'Mon – Wed (weekly) · 6:00pm GMT', 'hybrid', 1500, 30,
            ['HTML & CSS fundamentals', 'Responsive design', 'JavaScript essentials', 'PHP & MySQL', 'Deploying your site', 'Final project']],
        ['Network Administration Essentials', 'fa-network-wired', 'Beginner', 'Hands-on training in setting up, securing and troubleshooting office networks.', '4 weeks', 'Saturdays · 9:00am GMT', 'in-person', 1200, 45,
            ['Networking basics', 'Cabling and Wi-Fi setup', 'Routers and switches', 'Network security', 'Troubleshooting']],
        ['CCTV Installation & Maintenance', 'fa-video', 'Beginner', 'Learn to plan, install and maintain modern IP camera systems.', '2 weeks', 'Tue & Thu · 5:30pm GMT', 'in-person', 800, 60,
            ['Camera types and planning', 'Installation and cabling', 'NVR/DVR configuration', 'Remote viewing setup', 'Maintenance']],
        ['Introduction to Computing', 'fa-computer', 'Absolute beginner', 'Get confident with computers: typing, files, the internet, email and office tools for work and school.', '4 weeks', 'Saturdays · 10:00am GMT', 'hybrid', 500, 21,
            ['Using a computer and typing', 'Files and folders', 'Internet, email and online safety', 'Word processing and spreadsheets', 'Presentations']],
    ];

    private function posts(): void
    {
        if (Post::exists()) {
            return;
        }

        $rows = [
            ['5 signs your school is ready for a management system', 'Education',
                'Paper registers, fee receipts in drawers and late terminal reports? Here is how to tell it is time to go digital.'],
            ['Why bulk SMS still beats social media for urgent updates', 'Communication',
                'Not everyone checks social media, but nearly everyone reads a text. Here is how schools and churches use SMS well.'],
            ['Keeping your CCTV system healthy: a simple checklist', 'Security',
                'Cameras only protect you when they are recording. A five-minute monthly check keeps your system ready.'],
        ];

        foreach ($rows as $i => [$title, $category, $excerpt]) {
            Post::create([
                'title' => $title, 'category' => $category, 'excerpt' => $excerpt, 'author' => 'StarCliqs Team',
                'published_at' => now()->subDays(($i + 1) * 9),
                'body' => $excerpt . "\n\nThis is a sample post created during setup. Open Blog in the admin panel to edit it, or write your own and delete this one.",
            ]);
        }
    }
}
