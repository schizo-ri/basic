<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use PDO;
use App\Models\Client;

class DatabaseController extends Controller
{
   
    public static function create ($name, $url, $client_id) {

        $servername = "localhost";
        $username = "root";
        $password = "";

    //   $servername = "administracija.duplico.hr";
     //  $username = "duplicoh_jelena";
    //   $password = "Sifra123jj";
        
        try {

            $conn = new PDO("mysql:host=$servername", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE " . $name ." CHARACTER SET utf8 COLLATE utf8_general_ci";
            // use exec() because no results are returned
            $conn->exec($sql);

            DatabaseController::create_table($servername,  $username, $password, $name, $url, $client_id);

            echo "Database created successfully<br>";
            }
        catch(PDOException $e)
            {
            echo $sql . "<br>" . $e->getMessage();
            }
        
        $conn = null;
    }

    public static function create_table ( $servername,  $username, $password, $dbname, $url, $client_id) 
    { 
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            // sql to create table
            $sql = "CREATE TABLE absences (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                type varchar(50) NOT NULL COMMENT 'vrsta zahtjeva',
                employee_id int(11) NOT NULL,
                start_date date NOT NULL,
                end_date date NOT NULL,
                start_time time NOT NULL,
                end_time time NOT NULL,
                comment varchar(500) DEFAULT NULL,
                approve int(10) DEFAULT NULL,
                approved_id int(11) DEFAULT NULL COMMENT 'odobrio djelatnik',
                approved_date date DEFAULT NULL COMMENT 'datum odobrenja',
                approve_reason varchar(255) DEFAULT NULL COMMENT 'razlog odobrenja',
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE absence_types (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(50) NOT NULL COMMENT 'vrsta izostanka',
                mark varchar(5) NOT NULL,
                min_days int(11) DEFAULT NULL COMMENT 'minimalno dana GO',
                max_days int(11) DEFAULT NULL COMMENT 'maximalno dana GO',
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              INSERT INTO absence_types (id, name, mark, min_days, max_days, created_at, updated_at) VALUES
              (2, 'Godišnji odmor', 'GO', 20, 25, '2019-07-03 06:03:25', '2019-07-03 12:13:07'),
              (3, 'Izlazak', 'IZL', NULL, NULL, '2019-07-03 08:46:47', '2019-07-03 08:46:47'),
              (4, 'Bolovanje', 'BOL', NULL, NULL, '2019-08-06 22:00:00', '2019-08-06 22:00:00');
              
              /********************/
              CREATE TABLE activations (
                id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id int(10) UNSIGNED NOT NULL,
                code varchar(191) NOT NULL,
                completed tinyint(1) NOT NULL DEFAULT '0',
                completed_at timestamp NULL DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE ads (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                category_id int(11) NOT NULL,
                subject varchar(150) NOT NULL,
                description text NOT NULL,
                price varchar(100) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE ad_categories (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(255) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE comments (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                post_id int(11) NOT NULL,
                content text NOT NULL,
                status tinyint(4) NOT NULL DEFAULT '0',
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE companies (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(100) NOT NULL,
                address varchar(100) NOT NULL,
                city varchar(50) NOT NULL,
                oib varchar(20) NOT NULL,
                email varchar(50) DEFAULT NULL,
                phone varchar(50) DEFAULT NULL,
                director varchar(50) DEFAULT NULL,
                url varchar(255) NOT NULL,
                db varchar(255) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
             
              /********************/
              CREATE TABLE departments (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                company_id int(11) NOT NULL,
                name varchar(50) NOT NULL,
                level1 int(11) NOT NULL,
                level2 int(11) DEFAULT NULL,
                email varchar(50) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              
              CREATE TABLE department_roles (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                department_id int(11) NOT NULL,
                permissions text NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE documents (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                title varchar(255) NOT NULL,
                description varchar(255) DEFAULT NULL,
                path varchar(255) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE education (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(255) NOT NULL,
                to_department_id varchar(50) NOT NULL,
                status varchar(10) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE educations (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(255) NOT NULL,
                to_department_id varchar(50) NOT NULL,
                status varchar(10) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE education_articles (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                theme_id int(11) NOT NULL,
                subject varchar(100) NOT NULL,
                article mediumtext NOT NULL,
                status varchar(10) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE education_themes (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(100) NOT NULL,
                education_id int(11) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE emailings (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                model int(11) NOT NULL COMMENT 'table_id',
                method varchar(10) NOT NULL COMMENT 'slanje maila za određenu akciju',
                sent_to_dep varchar(255) DEFAULT NULL COMMENT 'slanje odjelu',
                sent_to_empl varchar(255) DEFAULT NULL COMMENT 'slanje djelatniku',
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE employees (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id int(11) NOT NULL,
                father_name varchar(20) DEFAULT NULL,
                mather_name varchar(20) DEFAULT NULL,
                oib varchar(20) NOT NULL,
                oi varchar(20) NOT NULL,
                oi_expiry date NOT NULL,
                b_day date NOT NULL,
                b_place varchar(50) DEFAULT NULL COMMENT 'mjesto rođenja',
                mobile varchar(50) DEFAULT NULL,
                priv_email varchar(50) DEFAULT NULL,
                email varchar(50) DEFAULT NULL,
                priv_mobile varchar(50) DEFAULT NULL,
                prebiv_adresa varchar(50) DEFAULT NULL,
                prebiv_grad varchar(50) DEFAULT NULL,
                borav_adresa varchar(50) DEFAULT NULL,
                borav_grad varchar(50) DEFAULT NULL,
                title varchar(50) DEFAULT NULL COMMENT 'zvanje',
                qualifications varchar(20) DEFAULT NULL COMMENT 'stručna sprema',
                marital varchar(10) NOT NULL COMMENT 'bračno stanje',
                work_id int(11) NOT NULL COMMENT 'radno mjesto',
                superior_id int(11) DEFAULT NULL COMMENT 'nadređeni djelatnik',
                reg_date date NOT NULL COMMENT 'datum prijave',
                probation int(11) DEFAULT NULL COMMENT 'probni rok, broj mjeseci',
                years_service varchar(10) DEFAULT NULL COMMENT 'godine staža',
                termination_service varchar(10) DEFAULT NULL COMMENT 'prekid staža',
                first_job varchar(10) DEFAULT NULL COMMENT 'prekid staža prije prijave',
                checkout date DEFAULT NULL COMMENT 'datum odjave',
                comment text COLLATE utf8_general_ci,
                effective_cost double(8,2) DEFAULT NULL COMMENT 'efektivna nijena sata rada',
                brutto double(8,2) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                /********************/
             
              CREATE TABLE evaluations (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id int(11) DEFAULT NULL COMMENT 'djelatnik koji ocjenjuje',
                employee_id int(11) DEFAULT NULL COMMENT 'djelatnik koji je ocjenjen',
                date date NOT NULL,
                questionnaire_id int(11) NOT NULL,
                category_id int(11) NOT NULL,
                question_id int(11) NOT NULL,
                koef double(8,2) NOT NULL,
                rating int(11) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE evaluation_answers (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                question_id int(11) NOT NULL,
                answer varchar(100) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE evaluation_categories (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                questionnaire_id int(11) NOT NULL,
                name_category varchar(255) NOT NULL,
                coefficient double(8,2) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE evaluation_employees (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                ev_employee_id int(11) NOT NULL COMMENT 'evaluated employee',
                questionnaire_id int(11) NOT NULL,
                mm_yy varchar(10) NOT NULL,
                status varchar(255) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE evaluation_questions (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                category_id int(11) NOT NULL,
                name_question varchar(255) NOT NULL,
                description text COLLATE utf8_general_ci,
                description2 text COLLATE utf8_general_ci,
                type varchar(3) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE evaluation_ratings (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(255) NOT NULL,
                rating tinyint(4) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE events (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                type varchar(20) DEFAULT NULL COMMENT 'tip eventa',
                title varchar(255) NOT NULL,
                description text NOT NULL,
                date date NOT NULL,
                time1 time NOT NULL,
                time2 time NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE migrations (
                id int(10) UNSIGNED NOT NULL,
                migration varchar(191) NOT NULL,
                batch int(11) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE notices (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                to_department varchar(100) NOT NULL COMMENT 'string, id odjela',
                to_employee varchar(100) DEFAULT NULL COMMENT 'string, id djelatnika',
                title varchar(100) NOT NULL,
                notice mediumtext NOT NULL,
                schedule_date datetime DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE users (
                id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                email varchar(191) NOT NULL,
                password varchar(191) NOT NULL,
                permissions text COLLATE utf8_general_ci,
                last_login timestamp NULL DEFAULT NULL,
                first_name varchar(191) DEFAULT NULL,
                last_name varchar(191) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE notice_statistics (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                notice_id int(11) NOT NULL,
                status int(11) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE persistences (
                id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id int(10) UNSIGNED NOT NULL,
                code varchar(191) UNIQUE KEY NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE posts (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                to_employee_id int(11) DEFAULT NULL,
                to_department_id int(11) DEFAULT NULL,
                title varchar(255) DEFAULT NULL,
                content text NOT NULL,
                status tinyint(4) NOT NULL DEFAULT '0' COMMENT 'last comment',
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE questionnaires (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(255) NOT NULL,
                description varchar(255) DEFAULT NULL,
                status int(11) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE questionnaire_results (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL COMMENT 'zaposlenik koji ocjenjuje',
                questionnaire_id int(11) NOT NULL,
                question_id int(11) NOT NULL,
                answer_id int(11) DEFAULT NULL,
                answer varchar(191) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE reminders (
                id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id int(10) UNSIGNED NOT NULL,
                code varchar(191) NOT NULL,
                completed tinyint(1) NOT NULL DEFAULT '0',
                completed_at timestamp NULL DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE roles (
                id int(10) UNSIGNED NOT NULL PRIMARY KEY,
                slug varchar(191) NOT NULL UNIQUE KEY ,
                name varchar(191) NOT NULL,
                permissions text COLLATE utf8_general_ci,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              INSERT INTO roles (id, slug, name, permissions, created_at, updated_at) VALUES
              (1, 'administrator', 'Administrator', '{\"absence_types.create\":true,\"absence_types.update\":true,\"absence_types.view\":true,\"absence_types.delete\":true,\"absences.create\":true,\"absences.update\":true,\"absences.view\":true,\"absences.delete\":true,\"ad_categories.create\":true,\"ad_categories.update\":true,\"ad_categories.view\":true,\"ad_categories.delete\":true,\"ads.create\":true,\"ads.update\":true,\"ads.view\":true,\"ads.delete\":true,\"comments.create\":true,\"comments.update\":true,\"comments.view\":true,\"comments.delete\":true,\"companies.create\":true,\"companies.update\":true,\"companies.view\":true,\"companies.delete\":true,\"department_roles.create\":true,\"department_roles.update\":true,\"department_roles.view\":true,\"department_roles.delete\":true,\"departments.create\":true,\"departments.update\":true,\"departments.view\":true,\"departments.delete\":true,\"documents.create\":true,\"documents.update\":true,\"documents.view\":true,\"documents.delete\":true,\"education.create\":true,\"education.update\":true,\"education.view\":true,\"education.delete\":true,\"education_articles.create\":true,\"education_articles.update\":true,\"education_articles.view\":true,\"education_articles.delete\":true,\"education_themes.create\":true,\"education_themes.update\":true,\"education_themes.view\":true,\"education_themes.delete\":true,\"emailings.create\":true,\"emailings.update\":true,\"emailings.view\":true,\"employees.create\":true,\"employees.update\":true,\"employees.view\":true,\"employees.delete\":true,\"evaluation_categories.create\":true,\"evaluation_categories.update\":true,\"evaluation_categories.view\":true,\"evaluation_categories.delete\":true,\"evaluation_questions.create\":true,\"evaluation_questions.update\":true,\"evaluation_questions.view\":true,\"evaluation_questions.delete\":true,\"evaluation_ratings.create\":true,\"evaluation_ratings.update\":true,\"evaluation_ratings.view\":true,\"evaluation_ratings.delete\":true,\"evaluations.create\":true,\"evaluations.update\":true,\"evaluations.view\":true,\"evaluations.delete\":true,\"events.create\":true,\"events.update\":true,\"events.view\":true,\"events.delete\":true,\"notices.create\":true,\"notices.update\":true,\"notices.view\":true,\"notices.delete\":true,\"posts.create\":true,\"posts.update\":true,\"posts.view\":true,\"posts.delete\":true,\"questionnaire_results.create\":true,\"questionnaire_results.update\":true,\"questionnaire_results.view\":true,\"questionnaire_results.delete\":true,\"questionnaires.create\":true,\"questionnaires.update\":true,\"questionnaires.view\":true,\"questionnaires.delete\":true,\"roles.create\":true,\"roles.update\":true,\"roles.view\":true,\"roles.delete\":true,\"tables.view\":true,\"users.create\":true,\"users.update\":true,\"users.view\":true,\"works.create\":true,\"works.update\":true,\"works.view\":true,\"works.delete\":true}', '2019-05-22 08:59:25', '2019-09-16 08:52:39'),
              (2, 'moderator', 'Moderator', '{\"users.update\":true,\"users.view\":true,\"companies.update\":true,\"companies.view\":true,\"departments.update\":true,\"departments.view\":true,\"works.update\":true,\"works.view\":true,\"employees.update\":true,\"employees.view\":true}', '2019-05-22 08:59:25', '2019-05-23 06:28:11'),
              (3, 'subscriber', 'Subscriber', '{\"absences.create\":true,\"absences.view\":true,\"ad_categories.create\":true,\"ad_categories.view\":true,\"ads.create\":true,\"ads.update\":true,\"ads.view\":true,\"ads.delete\":true,\"documents.view\":true,\"evaluation_questions.create\":true,\"evaluation_questions.view\":true,\"evaluations.create\":true,\"events.create\":true,\"events.view\":true,\"posts.create\":true,\"posts.view\":true,\"questionnaires.view\":true}', '2019-05-22 08:59:25', '2019-09-13 14:00:42'),
              (4, 'superadmin', 'SuperAdmin', '{\"absence_types.create\":true,\"absence_types.update\":true,\"absence_types.view\":true,\"absence_types.delete\":true,\"absences.create\":true,\"absences.update\":true,\"absences.view\":true,\"absences.delete\":true,\"ad_categories.create\":true,\"ad_categories.update\":true,\"ad_categories.view\":true,\"ad_categories.delete\":true,\"ads.create\":true,\"ads.update\":true,\"ads.view\":true,\"ads.delete\":true,\"comments.create\":true,\"comments.update\":true,\"comments.view\":true,\"comments.delete\":true,\"companies.create\":true,\"companies.update\":true,\"companies.view\":true,\"companies.delete\":true,\"department_roles.create\":true,\"department_roles.update\":true,\"department_roles.view\":true,\"department_roles.delete\":true,\"departments.create\":true,\"departments.update\":true,\"departments.view\":true,\"departments.delete\":true,\"documents.create\":true,\"documents.update\":true,\"documents.view\":true,\"documents.delete\":true,\"education.create\":true,\"education.update\":true,\"education.view\":true,\"education.delete\":true,\"education_articles.create\":true,\"education_articles.update\":true,\"education_articles.view\":true,\"education_articles.delete\":true,\"education_themes.create\":true,\"education_themes.update\":true,\"education_themes.view\":true,\"education_themes.delete\":true,\"emailings.create\":true,\"emailings.update\":true,\"emailings.view\":true,\"emailings.delete\":true,\"employees.create\":true,\"employees.update\":true,\"employees.view\":true,\"employees.delete\":true,\"evaluation_categories.create\":true,\"evaluation_categories.update\":true,\"evaluation_categories.view\":true,\"evaluation_categories.delete\":true,\"evaluation_questions.create\":true,\"evaluation_questions.update\":true,\"evaluation_questions.view\":true,\"evaluation_questions.delete\":true,\"evaluation_ratings.create\":true,\"evaluation_ratings.update\":true,\"evaluation_ratings.view\":true,\"evaluation_ratings.delete\":true,\"evaluations.create\":true,\"evaluations.update\":true,\"evaluations.view\":true,\"evaluations.delete\":true,\"events.create\":true,\"events.update\":true,\"events.view\":true,\"events.delete\":true,\"notices.create\":true,\"notices.update\":true,\"notices.view\":true,\"notices.delete\":true,\"posts.create\":true,\"posts.update\":true,\"posts.view\":true,\"posts.delete\":true,\"questionnaire_results.create\":true,\"questionnaire_results.update\":true,\"questionnaire_results.view\":true,\"questionnaire_results.delete\":true,\"questionnaires.create\":true,\"questionnaires.update\":true,\"questionnaires.view\":true,\"questionnaires.delete\":true,\"roles.create\":true,\"roles.update\":true,\"roles.view\":true,\"roles.delete\":true,\"tables.create\":true,\"tables.update\":true,\"tables.view\":true,\"tables.delete\":true,\"users.create\":true,\"users.update\":true,\"users.view\":true,\"users.delete\":true,\"works.create\":true,\"works.update\":true,\"works.view\":true,\"works.delete\":true}', '2019-06-28 04:37:14', '2019-09-16 08:53:02');
              /********************/
              CREATE TABLE role_users (
                user_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                role_id int(10) UNSIGNED NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              
              /********************/
              CREATE TABLE tables (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name varchar(255) NOT NULL,
                description varchar(255) NOT NULL,
                emailing tinyint(4) NOT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              INSERT INTO tables (id, name, description, emailing, created_at, updated_at) VALUES
              (1, 'ad_categories', 'Kategorije oglasa', 0, '2019-06-28 04:52:13', '2019-06-28 05:00:21'),
              (2, 'ads', 'Oglasi', 1, '2019-06-28 05:01:34', '2019-06-28 05:01:34'),
              (3, 'users', 'Korisnici aplikacije', 1, '2019-06-28 05:01:55', '2019-06-28 05:01:55'),
              (4, 'comments', 'Komentari na poruke', 1, '2019-06-28 05:02:15', '2019-06-28 05:02:15'),
              (5, 'companies', 'Tvrtke', 0, '2019-06-28 05:02:31', '2019-06-28 05:02:31'),
              (6, 'department_roles', 'Dopuštenja odjela', 0, '2019-06-28 05:02:47', '2019-06-28 05:02:47'),
              (7, 'departments', 'Odjeli', 0, '2019-06-28 05:02:55', '2019-06-28 05:02:55'),
              (8, 'documents', 'Dokumenti', 0, '2019-06-28 05:03:12', '2019-06-28 05:03:12'),
              (9, 'education', 'Edukacije', 0, '2019-06-28 05:03:24', '2019-06-28 05:03:24'),
              (10, 'education_themes', 'Teme edukacije', 0, '2019-06-28 05:03:37', '2019-06-28 05:03:37'),
              (11, 'education_articles', 'Članci edukacije', 1, '2019-06-28 05:03:56', '2019-06-28 05:03:56'),
              (12, 'employees', 'Zaposlenici', 1, '2019-06-28 05:04:07', '2019-06-28 05:04:07'),
              (13, 'evaluation_categories', 'Kategorije evaluacije', 0, '2019-06-28 05:04:26', '2019-06-28 05:04:26'),
              (14, 'evaluation_questions', 'Evaluacijska pitanja', 0, '2019-06-28 05:04:50', '2019-06-28 05:04:50'),
              (15, 'evaluation_ratings', 'Ocjene evaluacije', 0, '2019-06-28 05:05:06', '2019-06-28 05:05:06'),
              (16, 'events', 'Događanja, kalkendar', 1, '2019-06-28 05:05:19', '2019-06-28 05:05:19'),
              (17, 'posts', 'Poruke djelatnicima i odjelima', 1, '2019-06-28 05:05:37', '2019-06-28 05:05:37'),
              (18, 'questionnaires', 'Ankete', 1, '2019-06-28 05:05:51', '2019-06-28 05:05:51'),
              (19, 'works', 'Radna mjesta', 0, '2019-06-28 05:06:03', '2019-06-28 05:06:03'),
              (20, 'absences', 'Izostanci djelatnika', 1, '2019-07-02 11:52:36', '2019-07-02 11:52:36'),
              (21, 'tables', 'Tabele', 0, '2019-07-02 12:11:46', '2019-07-02 12:11:46'),
              (22, 'roles', 'Uloge', 0, '2019-07-02 12:11:56', '2019-07-02 12:11:56'),
              (23, 'absence_types', 'Vrste izostanaka', 0, '2019-07-03 05:30:12', '2019-07-03 05:30:12'),
              (24, 'emailings', 'Slanje mailova', 0, '2019-07-04 04:44:38', '2019-07-04 04:44:47'),
              (25, 'notices', 'Obavijesti za djelatnike', 1, '2019-08-06 12:07:13', '2019-08-06 12:07:13'),
              (26, 'evaluations', 'Ocjenjivanje anketa', 0, '2019-09-06 13:24:04', '2019-09-06 13:24:04'),
              (28, 'questionnaire_results', 'Rezultati anketa', 0, '2019-09-16 08:41:45', '2019-09-16 08:41:45');
              /********************/
              CREATE TABLE throttle (
                id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id int(10) UNSIGNED DEFAULT NULL,
                type varchar(191) NOT NULL,
                ip varchar(191) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE user_interes (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employee_id int(11) NOT NULL,
                category varchar(255) DEFAULT NULL,
                description text COLLATE utf8_general_ci,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              /********************/
              CREATE TABLE works (
                id int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                department_id int(11) NOT NULL,
                name varchar(255) NOT NULL,
                job_description varchar(255) DEFAULT NULL,
                employee_id int(11) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
              ALTER TABLE throttle
                ADD KEY throttle_user_id_index (user_id);";
           
            // use exec() because no results are returned
            $conn->exec($sql);
            DatabaseController::insert($servername,  $username, $password, $dbname, $url,$client_id);
            }
        catch(PDOException $e)
            {
            echo $sql . "<br>" . $e->getMessage();
            }
        
        $conn = null;
    }

    public static function insert($servername,  $username, $password, $dbname, $url, $client_id) 
    {
        $client = Client::find($client_id);
        $clint_Data = "1, '" . $client->name . "', '" . $client->address . "', '" . $client->city . "', '" . $client->oib . "', '" . $client->email . "', '" . $client->phone  . "', '" . $url . "', '" . $dbname . "'";
      
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO companies (id, name, address, city, oib, email, phone, url, db) VALUES (" .  $clint_Data . "); " . 
            "INSERT INTO departments (id, company_id, name, level1, level2) VALUES
            (1, 1, 'Svi', 0, 0);".
            "INSERT INTO works (id,department_id, name) VALUES
            (1, 1, 'Odjel 1');".
            "INSERT INTO users (id, email, password, first_name,last_name) VALUES
            (1, '" . $client->email . "','123456789', '" . $client->first_name . "', '" . $client->first_name . "');".
            "INSERT INTO role_users (user_id, role_id) VALUES
             (1, 1);".
            "INSERT INTO employees (id,user_id, work_id) VALUES
            (1, 1, 1);";
            // use exec() because no results are returned
            $conn->exec($sql);
           
            }
        catch(PDOException $e)
            {
            echo $sql . "<br>" . $e->getMessage();
            }

        $conn = null;

    }
}
