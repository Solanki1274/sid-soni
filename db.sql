-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('admin', 'client') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dob DATE NULL,
    address TEXT NULL,
    gender ENUM('male', 'female', 'other') NULL,
    security_question VARCHAR(255) NULL,
    security_answer VARCHAR(255) NULL
);

-- Create services table
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    features TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration INT NOT NULL, -- Duration in minutes
    category VARCHAR(50) DEFAULT 'General', -- Service category
    status ENUM('active', 'inactive') DEFAULT 'active', -- Service status
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Last updated time
   
    image_url VARCHAR(255) -- Path or URL of the service image
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    features TEXT NOT NULL,
    client_name VARCHAR(255),
    client_testimonial TEXT,
    project_url VARCHAR(255),
    completion_date DATE
);

INSERT INTO projects (title, description, image_url, features, client_name, client_testimonial, project_url, completion_date)
VALUES 
    ('Custom CRM Development', 'A custom CRM developed for managing client relationships and data.', 'images/crm-project.jpg', 'CRM Integration, Cloud Storage, Real-time Analytics', 'Tech Solutions Ltd.', 'The CRM solution transformed our workflow!', 'https://www.techagency.com/project/crm', '2024-01-15'),
    ('AI-Powered Chatbot', 'An AI-powered chatbot to automate customer support and increase engagement.', 'images/chatbot-project.jpg', 'AI, Natural Language Processing, Integration', 'SmartAI Technologies', 'AI Chatbot has revolutionized customer service.', 'https://www.techagency.com/project/chatbot', '2023-12-30'),
    ('E-Commerce Website', 'An e-commerce platform built to manage online stores and transactions.', 'images/e-commerce-project.jpg', 'User-friendly Interface, Secure Payments, Multi-currency Support', 'E-Shop Enterprises', 'The e-commerce platform has boosted our sales by 40%.', 'https://www.techagency.com/project/e-commerce', '2023-11-25');


-- Create payments table
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('Pending', 'Completed', 'Failed') NOT NULL,
    notes TEXT
);

-- Create bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    special_requests TEXT,
    payment_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (payment_id) REFERENCES payments(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);
-- Insert sample data into the users table
INSERT INTO users (username, email, password, full_name, phone, role, dob, address, gender, security_question, security_answer) VALUES
('sidhart soni', 'admin@example.com', 'sidsoni', 'Sidhhart Soni', '1234567890', 'admin', '1990-01-01', 'Admin Office', 'male', 'What is your favorite color?', 'blue'),
('tech_client1', 'client1@example.com', 'clientpass', 'John Doe', '9876543210', 'client', '1995-05-15', '123 Tech Park', 'male', 'What is your first pet’s name?', 'buddy'),
('tech_client2', 'client2@example.com', 'clientpass', 'Jane Smith', '9876500001', 'client', '1992-08-20', '456 Innovation Street', 'female', 'What was your childhood nickname?', 'janie');

-- Insert sample data into the services table
INSERT INTO services (name, description, price, duration, category, status, image_url) VALUES
('Web Development', 'Professional web design and development services tailored to your business needs.', 800.00, 720, 'Tech', 'active', 10.00, 'images/webdev.jpg'),
('Digital Marketing', 'Comprehensive digital marketing services including SEO, PPC, and social media campaigns.', 600.00, 480, 'Tech', 'active', 8.00, 'images/digital_marketing.jpg'),
('Mobile App Development', 'Custom mobile app development for Android and iOS platforms.', 1200.00, 1440, 'Tech', 'active', 15.00, 'images/mobile_app.jpg'),
('Tech Consultation', 'Consultation for technology solutions to grow your business.', 200.00, 120, 'Tech', 'active', 5.00, 'images/tech_consultation.jpg'),
('Cybersecurity Audit', 'Detailed audit and recommendations to improve your company’s cybersecurity.', 300.00, 180, 'Tech', 'active', 7.00, 'images/cybersecurity.jpg');

-- Insert sample data into the bookings table
INSERT INTO bookings (user_id, service_id, booking_date, booking_time, status, special_requests, payment_id) VALUES
(2, 1, '2025-01-25', '10:00:00', 'confirmed', 'Need a modern and responsive design.', 1), -- Matches payment id 1
(3, 2, '2025-01-26', '14:00:00', 'pending', 'Focus on increasing social media engagement.', 2), -- Matches payment id 2
(2, 3, '2025-01-27', '09:30:00', 'completed', 'Build an e-commerce application.', 3), -- Matches payment id 3
(3, 4, '2025-01-28', '11:00:00', 'cancelled', 'Need advice on cloud solutions.', NULL), -- No payment, set NULL
(2, 5, '2025-01-29', '15:00:00', 'confirmed', 'Ensure compliance with security regulations.', 4); -- Matches payment id 4


-- Insert sample data into the payments table
INSERT INTO payments (customer_name, amount_paid, payment_method, payment_status, notes) VALUES
('John Doe', 720.00, 'Credit Card', 'Completed', 'Payment for Web Development service.'),
('harsh', 550.00, 'UPI', 'Completed', 'Advance payment for Digital Marketing service.'),
('John Doe', 1020.00, 'Bank Transfer', 'Completed', 'Full payment for Mobile App Development.'),
('John Doe', 285.00, 'Credit Card', 'Completed', 'Payment for Cybersecurity Audit.');

