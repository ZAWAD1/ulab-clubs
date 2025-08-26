-- init.sql
DROP DATABASE IF EXISTS ulab_club;
CREATE DATABASE ulab_club CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ulab_club;

-- Users
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
student_id VARCHAR(20) NOT NULL UNIQUE,
name VARCHAR(100) NOT NULL,
email VARCHAR(120) NOT NULL UNIQUE,
password_hash VARCHAR(255) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Clubs
CREATE TABLE clubs (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL UNIQUE,
description TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Each user can be a member of **one** club only
CREATE TABLE memberships (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
club_id INT NOT NULL,
joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
UNIQUE KEY uniq_user (user_id),
CONSTRAINT fk_memberships_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
CONSTRAINT fk_memberships_club FOREIGN KEY (club_id) REFERENCES clubs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seed a few clubs
INSERT INTO clubs (name, description) VALUES
('Computer Programming Club', 'Competitive programming, coding contests, and problem-solving sessions.'),
('Business Club', 'Case comps, entrepreneurship talks, and networking events.'),
('Robotics Club', 'Robots, IoT tinkering, and hardware projects.'),
('Debating Club', 'British Parliamentary practice, adjudication workshops, and tournaments.');