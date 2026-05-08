-- ============================================================
--  University Library Management System — Database Schema
--  Run this file once to set up your MySQL database
-- ============================================================

CREATE DATABASE IF NOT EXISTS university_library
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE university_library;

CREATE TABLE IF NOT EXISTS books (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255)  NOT NULL,
    author      VARCHAR(255)  NOT NULL,
    category    VARCHAR(100)  NOT NULL,
    status      ENUM('available','borrowed','reserved') NOT NULL DEFAULT 'available',
    added_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample seed data
INSERT INTO books (title, author, category, status) VALUES
('Clean Code',                    'Robert C. Martin',   'Programming',  'available'),
('The Great Gatsby',              'F. Scott Fitzgerald','Fiction',       'borrowed'),
('Introduction to Algorithms',    'Cormen et al.',      'Computer Science','available'),
('Sapiens',                       'Yuval Noah Harari',  'History',       'available'),
('Design Patterns',               'Gang of Four',       'Programming',   'reserved'),
('To Kill a Mockingbird',         'Harper Lee',         'Fiction',       'available'),
('Database System Concepts',      'Silberschatz et al.','Computer Science','borrowed'),
('Thinking, Fast and Slow',       'Daniel Kahneman',    'Psychology',    'available');
