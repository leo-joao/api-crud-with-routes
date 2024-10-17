CREATE TABLE categories (
    id INT(10) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255),
    situation BOOLEAN,
    PRIMARY KEY (id)
);

CREATE TABLE measures (
    id INT(10) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255),
    unit VARCHAR(5),
    PRIMARY KEY (id)
);

CREATE TABLE products (
    id INT(10) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2),
    quantity DECIMAL(10, 4),
    measure INT(10),
    category INT(10),
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME,
    situation BOOLEAN,
    PRIMARY KEY (id),
    FOREIGN KEY (category) REFERENCES categories (id),
    FOREIGN KEY (measure) REFERENCES measures (id)
);

CREATE TABLE stock_movements (
    id INT(10) NOT NULL AUTO_INCREMENT,
    product INT(10),
    quantity DECIMAL(10, 4),
    movement_type ENUM('in', 'out'),
    movement_date DATETIME,
    PRIMARY KEY (id),
    FOREIGN KEY (product) REFERENCES products (id)
);

CREATE TABLE supplier_addresses (
    id INT(10) NOT NULL AUTO_INCREMENT,
    address_description VARCHAR(255),
    state VARCHAR(2),
    city VARCHAR(255),
    zip_code VARCHAR(20),
    situation BOOLEAN,
    PRIMARY KEY (id)
);

CREATE TABLE supplier_telephones (
    id INT(10) NOT NULL AUTO_INCREMENT,
    phone_description VARCHAR(255),
    country_code VARCHAR(255),
    number VARCHAR(20),
    situation BOOLEAN,
    PRIMARY KEY (id)
);

CREATE TABLE supplier_emails (
    id INT(10) NOT NULL AUTO_INCREMENT,
    email_description VARCHAR(255),
    country_code VARCHAR(255),
    number VARCHAR(20),
    situation BOOLEAN,
    PRIMARY KEY (id)
);

CREATE TABLE suppliers (
    id INT(10) NOT NULL AUTO_INCREMENT,
    cpf VARCHAR(14) UNIQUE,
    cnpj VARCHAR(20) UNIQUE,
    name VARCHAR(255),
    main_address INT,
    main_phone INT,
    main_email INT,
    PRIMARY KEY (id),
    FOREIGN KEY (main_address) REFERENCES supplier_addresses (id),
    FOREIGN KEY (main_phone) REFERENCES supplier_telephones (id),
    FOREIGN KEY (main_email) REFERENCES supplier_emails (id)
);

CREATE TABLE users (
    id INT(10) NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

-- Índice para a coluna `name` na tabela `products`
CREATE INDEX idx_name ON products (name);

-- Índice para a coluna `cpf` na tabela `suppliers`
CREATE INDEX idx_cpf ON suppliers (cpf);

-- Índice para a coluna `cnpj` na tabela `suppliers`
CREATE INDEX idx_cnpj ON suppliers (cnpj);
