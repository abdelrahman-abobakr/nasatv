-- Create employee user
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES ('Employee User', 'employee@nasatv.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', NOW(), NOW());

-- Create sample plans
INSERT INTO plans (name, price, duration, description, status, created_at, updated_at) 
VALUES 
('Basic Plan', 29.99, '1 Month', 'Basic subscription plan with essential features', 'active', NOW(), NOW()),
('Premium Plan', 79.99, '3 Months', 'Premium subscription plan with advanced features', 'active', NOW(), NOW()),
('Annual Plan', 299.99, '1 Year', 'Annual subscription plan with all features', 'active', NOW(), NOW());
