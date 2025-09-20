-- USERS TABLE
CREATE TABLE fiverr_clone_users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT,
    role ENUM('client','freelancer','fiverr_administrator') DEFAULT 'client', -- NEW: replaces is_client
    bio_description TEXT,
    display_picture TEXT,
    contact_number VARCHAR(255),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CATEGORIES
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- SUBCATEGORIES
CREATE TABLE subcategories (
    subcategory_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- PROPOSALS
CREATE TABLE proposals (
    proposal_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subcategory_id INT NULL, -- NEW: classification
    description TEXT,
    image TEXT,
    min_price INT,
    max_price INT,
    view_count INT DEFAULT 0,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES fiverr_clone_users(user_id),
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(subcategory_id) ON DELETE SET NULL
);

-- OFFERS
CREATE TABLE offers (
    offer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, -- client_id (or admin acting as client)
    description TEXT,
    proposal_id INT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES fiverr_clone_users(user_id),
    FOREIGN KEY (proposal_id) REFERENCES proposals(proposal_id),
    UNIQUE (user_id, proposal_id) -- ensures only one offer per client per proposal
);
