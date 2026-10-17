<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;
    private static ?int $connectedPort = null;

    public static function getConnection(): PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $host = $config['host'];
        $ports = (array)$config['ports'];
        $dbName = $config['database'];
        $username = $config['username'];
        $password = $config['password'];
        $charset = $config['charset'];

        $lastException = null;

        foreach ($ports as $port) {
            try {
                // First test connection to MySQL server
                $dsnWithoutDb = "mysql:host={$host};port={$port};charset={$charset}";
                $testPdo = new PDO($dsnWithoutDb, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 2
                ]);

                // Ensure database exists
                $testPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

                // Connect directly to the database
                $dsnWithDb = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
                self::$instance = new PDO($dsnWithDb, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);

                self::$connectedPort = $port;
                break;
            } catch (PDOException $e) {
                $lastException = $e;
                continue;
            }
        }

        if (self::$instance === null) {
            die("Database Connection Error: Unable to connect to MySQL on ports " . implode(', ', $ports) . ". Error: " . ($lastException ? $lastException->getMessage() : 'Unknown error'));
        }

        // Check if schema needs to be initialized
        self::ensureSchemaInitialized();

        return self::$instance;
    }

    public static function getConnectedPort(): ?int {
        return self::$connectedPort;
    }

    private static function ensureSchemaInitialized(): void {
        try {
            $stmt = self::$instance->query("SHOW TABLES LIKE 'users'");
            if ($stmt->rowCount() === 0) {
                self::runMigrationsAndSeed();
            }
        } catch (\Exception $e) {
            // Ignore error or log
        }
    }

    public static function runMigrationsAndSeed(): void {
        $schemaFile = dirname(__DIR__, 2) . '/database/schema.sql';
        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            // Execute schema commands
            self::$instance->exec($sql);
            // Seed sample data
            self::seedInitialData();
        }
    }

    public static function seedInitialData(): void {
        $pdo = self::$instance;

        // Check if users already seeded
        $check = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($check > 0) {
            return;
        }

        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        $adminHash = password_hash('admin123', PASSWORD_DEFAULT);

        // 1. Insert Admin
        $stmtUser = $pdo->prepare("INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)");
        $stmtUser->execute(['Admin System', 'admin@homefix.com', $adminHash, 'admin', '9999900000']);

        // 2. Insert Customers
        $stmtUser->execute(['Arun Verma', 'arun@example.com', $passwordHash, 'customer', '9820011223']);
        $arunId = $pdo->lastInsertId();

        $stmtUser->execute(['Neha Gupta', 'neha@example.com', $passwordHash, 'customer', '9820044556']);
        $nehaId = $pdo->lastInsertId();

        $stmtUser->execute(['Vikas Deshmukh', 'vikas@example.com', $passwordHash, 'customer', '9820077889']);
        $vikasId = $pdo->lastInsertId();

        // 3. Insert Service Providers & Profiles
        // Matching rough sketches from PPT Slide 6:
        // Ramesh Kumar - Electrician (4.6 stars, 28 reviews, Andheri)
        // Sunita Das - Plumber (4.8 stars, Dadar)
        $providersData = [
            [
                'name' => 'Ramesh Kumar',
                'email' => 'ramesh.electrician@example.com',
                'phone' => '9821012345',
                'service_type' => 'Electrician',
                'city' => 'Mumbai',
                'area' => 'Andheri',
                'experience' => 9,
                'bio' => 'Licensed electrical expert with 9+ years experience handling home wiring, circuit repairs, smart switchboards, and safety installations across Andheri and Western Mumbai.',
                'hourly_rate' => 499.00,
                'rating' => 4.6,
                'review_count' => 28,
                'address' => 'Shop 4, Greenfield Plaza, Near Andheri West Metro, Mumbai',
                'latitude' => 19.1197,
                'longitude' => 72.8464,
                'services' => [
                    ['Wiring repair & short circuit fix', 'Electrician', 499.00, 'Diagnose tripping MCB, burned wires, and electrical faults safely.'],
                    ['Ceiling fan & light fitting', 'Electrician', 299.00, 'Installation or replacement of decorative lights, chandeliers, and high-speed fans.'],
                    ['Complete switchboard rewiring', 'Electrician', 799.00, 'Modular board installation with surge protection and earthing check.']
                ]
            ],
            [
                'name' => 'Sunita Das',
                'email' => 'sunita.plumbing@example.com',
                'phone' => '9821023456',
                'service_type' => 'Plumber',
                'city' => 'Mumbai',
                'area' => 'Dadar',
                'experience' => 7,
                'bio' => 'Experienced certified plumber specializing in leak diagnostics, bathroom fixture replacements, overhead tank connections, and high-pressure jet cleaning.',
                'hourly_rate' => 399.00,
                'rating' => 4.8,
                'review_count' => 34,
                'address' => '12 Shivneri Heights, Gokhale Road, Dadar West, Mumbai',
                'latitude' => 19.0178,
                'longitude' => 72.8478,
                'services' => [
                    ['Bathroom leak detection & tap fitting', 'Plumber', 399.00, 'Fix leaky taps, mixer showers, angle valves, and flush tanks.'],
                    ['Drain blockage removal & jetting', 'Plumber', 599.00, 'High-powered unclogging for kitchen sinks, floor traps, and waste pipes.'],
                    ['Water heater (Geyser) installation & piping', 'Plumber', 749.00, 'Inlet/outlet connection with safety pressure valve check.']
                ]
            ],
            [
                'name' => 'Amit Sharma',
                'email' => 'amit.carpentry@example.com',
                'phone' => '9821034567',
                'service_type' => 'Carpenter',
                'city' => 'Mumbai',
                'area' => 'Bandra',
                'experience' => 11,
                'bio' => 'Master artisan carpenter providing bespoke wooden furniture repairs, modular kitchen hinges adjustments, door lock fixing, and tailored shelving.',
                'hourly_rate' => 549.00,
                'rating' => 4.7,
                'review_count' => 22,
                'address' => 'Plot 88, Hill Road Art Studios, Bandra West, Mumbai',
                'latitude' => 19.0596,
                'longitude' => 72.8295,
                'services' => [
                    ['Door lock & hydraulic hinge fix', 'Carpenter', 449.00, 'Fix misaligned doors, soft-close hydraulic dampers, and Godrej locks.'],
                    ['Custom wall shelving & unit assembly', 'Carpenter', 799.00, 'Floating wooden shelves, IKEA furniture assembly, and bracket mounting.'],
                    ['Furniture polish & structural refurbish', 'Carpenter', 1299.00, 'Melamine/PU touchup and joint reinforcement for dining and sofa sets.']
                ]
            ],
            [
                'name' => 'Rajesh Patel',
                'email' => 'rajesh.appliances@example.com',
                'phone' => '9821045678',
                'service_type' => 'Appliance Repair',
                'city' => 'Mumbai',
                'area' => 'Powai',
                'experience' => 8,
                'bio' => 'Specialist in inverter AC servicing, refrigerator gas refilling, front-load washing machine diagnostics, and microwave oven maintenance.',
                'hourly_rate' => 649.00,
                'rating' => 4.9,
                'review_count' => 41,
                'address' => 'TechZone Complex, Central Avenue, Hiranandani, Powai, Mumbai',
                'latitude' => 19.1176,
                'longitude' => 72.9060,
                'services' => [
                    ['Split AC deep foam jet servicing', 'Appliance Repair', 599.00, 'Pressure wash indoor & outdoor coils, clean filter, and check refrigerant gas.'],
                    ['Washing machine motor & drain pump fix', 'Appliance Repair', 699.00, 'Diagnose spin cycle noise, water drainage issues, and PCB errors.'],
                    ['Refrigerator cooling & thermostat check', 'Appliance Repair', 549.00, 'Fix frost build-up, compressor relay, and cooling coil repairs.']
                ]
            ],
            [
                'name' => 'Priya Nair',
                'email' => 'priya.cleaning@example.com',
                'phone' => '9821056789',
                'service_type' => 'Cleaning',
                'city' => 'Mumbai',
                'area' => 'Matunga',
                'experience' => 6,
                'bio' => 'Eco-friendly deep cleaning expert. Leads an efficient team equipped with industrial vacuum cleaners, single-disc floor scrubbers, and safe sanitized detergents.',
                'hourly_rate' => 899.00,
                'rating' => 4.8,
                'review_count' => 37,
                'address' => 'Green View, King’s Circle, Matunga East, Mumbai',
                'latitude' => 19.0274,
                'longitude' => 72.8553,
                'services' => [
                    ['Kitchen deep degreasing & tile scrub', 'Cleaning', 1199.00, 'Exhaust fan chimney clean, cabinet interior/exterior, and oil stain removal.'],
                    ['Bathroom sanitization & descaling', 'Cleaning', 799.00, 'Tile grout scrubbing, glass partition limescale removal, and tap shining.'],
                    ['Full 2BHK deep home clean', 'Cleaning', 2799.00, 'End-to-end vacuuming, wet mopping, window track cleaning, and balcony wash.']
                ]
            ],
            [
                'name' => 'Vikram Malhotra',
                'email' => 'vikram.painting@example.com',
                'phone' => '9821067890',
                'service_type' => 'Painting',
                'city' => 'Mumbai',
                'area' => 'Chembur',
                'experience' => 10,
                'bio' => 'Professional interior and exterior house painter. Precision wall putty, waterproof damp-seal treatment, accent walls, and low-VOC royal luxury sheen.',
                'hourly_rate' => 1299.00,
                'rating' => 4.5,
                'review_count' => 19,
                'address' => 'Sai Ashish Colony, Station Road, Chembur East, Mumbai',
                'latitude' => 19.0522,
                'longitude' => 72.8994,
                'services' => [
                    ['Wall dampness treatment & putty base', 'Painting', 1499.00, 'Waterproof primer coat, crack filling, and anti-fungal treatment.'],
                    ['Feature accent wall design painting', 'Painting', 1899.00, 'Geometric, metallic or textured designer wall for living rooms.'],
                    ['Single room touch-up & ceiling repaint', 'Painting', 2499.00, 'Two coats of premium plastic emulsion with clean floor masking.']
                ]
            ]
        ];

        $stmtProvider = $pdo->prepare("INSERT INTO providers (user_id, service_type, city, area, experience, bio, hourly_rate, rating, review_count, address, latitude, longitude, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        $stmtService = $pdo->prepare("INSERT INTO services (provider_id, service_name, category, price, description) VALUES (?, ?, ?, ?, ?)");

        $providerMap = []; // provider_name => ['provider_id' => X, 'service_ids' => [Y1, Y2]]

        foreach ($providersData as $pData) {
            // Create user for provider
            $stmtUser->execute([$pData['name'], $pData['email'], $passwordHash, 'provider', $pData['phone']]);
            $userId = $pdo->lastInsertId();

            // Create provider record
            $stmtProvider->execute([
                $userId,
                $pData['service_type'],
                $pData['city'],
                $pData['area'],
                $pData['experience'],
                $pData['bio'],
                $pData['hourly_rate'],
                $pData['rating'],
                $pData['review_count'],
                $pData['address'],
                $pData['latitude'],
                $pData['longitude']
            ]);
            $providerId = $pdo->lastInsertId();

            $serviceIds = [];
            foreach ($pData['services'] as $srv) {
                $stmtService->execute([
                    $providerId,
                    $srv[0],
                    $srv[1],
                    $srv[2],
                    $srv[3]
                ]);
                $serviceIds[] = $pdo->lastInsertId();
            }

            $providerMap[$pData['name']] = [
                'provider_id' => $providerId,
                'user_id' => $userId,
                'service_ids' => $serviceIds
            ];
        }

        // 4. Insert Seed Bookings & Reviews
        // Let's create realistic bookings matching the 6-step lifecycle
        $stmtBooking = $pdo->prepare("INSERT INTO bookings (user_id, provider_id, service_id, date, time, address, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtReview = $pdo->prepare("INSERT INTO reviews (booking_id, user_id, provider_id, rating, comment) VALUES (?, ?, ?, ?, ?)");

        // Booking 1: Arun booked Ramesh Kumar (Electrician) -> Completed + 5-star Review
        $ramesh = $providerMap['Ramesh Kumar'];
        $stmtBooking->execute([
            $arunId,
            $ramesh['provider_id'],
            $ramesh['service_ids'][0],
            date('Y-m-d', strtotime('-3 days')),
            '10:30:00',
            'Flat 402, Sea Crest Towers, Juhu Tara Road, Andheri West, Mumbai',
            'Frequent tripping in master bedroom switchboard during AC use.',
            'Completed'
        ]);
        $b1Id = $pdo->lastInsertId();
        $stmtReview->execute([
            $b1Id,
            $arunId,
            $ramesh['provider_id'],
            5,
            'Ramesh arrived right on time with all diagnostic gear! Found a damaged wire junction within 15 minutes and repaired it cleanly. Very respectful and knowledgeable pro.'
        ]);

        // Booking 2: Neha booked Sunita Das (Plumber) -> Completed + 5-star Review
        $sunita = $providerMap['Sunita Das'];
        $stmtBooking->execute([
            $nehaId,
            $sunita['provider_id'],
            $sunita['service_ids'][0],
            date('Y-m-d', strtotime('-5 days')),
            '14:00:00',
            'B/14 Chitra Cinema Lane, Dr. Ambedkar Road, Dadar East, Mumbai',
            'Shower mixer knob leaking continuously into bucket.',
            'Completed'
        ]);
        $b2Id = $pdo->lastInsertId();
        $stmtReview->execute([
            $b2Id,
            $nehaId,
            $sunita['provider_id'],
            5,
            'Sunita is super punctual and solved our shower leak immediately with genuine brass parts. No mess left behind. Truly a reliable neighbourhood plumber!'
        ]);

        // Booking 3: Vikas booked Rajesh Patel (Appliance Repair) -> Accepted (Upcoming)
        $rajesh = $providerMap['Rajesh Patel'];
        $stmtBooking->execute([
            $vikasId,
            $rajesh['provider_id'],
            $rajesh['service_ids'][0],
            date('Y-m-d', strtotime('+1 day')),
            '11:00:00',
            'Tower 3, Apt 1102, Lake Homes, Powai, Mumbai',
            'Need deep jet foam cleaning before summer heatwave begins.',
            'Accepted'
        ]);

        // Booking 4: Arun booked Amit Sharma (Carpenter) -> Pending (New request)
        $amit = $providerMap['Amit Sharma'];
        $stmtBooking->execute([
            $arunId,
            $amit['provider_id'],
            $amit['service_ids'][0],
            date('Y-m-d', strtotime('+2 days')),
            '16:30:00',
            'Flat 402, Sea Crest Towers, Juhu Tara Road, Bandra West, Mumbai',
            'Hydraulic cabinet door hinges in modular kitchen are loose.',
            'Pending'
        ]);
    }
}
