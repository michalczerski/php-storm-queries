CREATE TABLE categories
(
    category_id   int NOT NULL,
    category_name varchar(255) DEFAULT NULL,
    description   varchar(255) DEFAULT NULL
);

CREATE TABLE customers
(
    customer_id   int NOT NULL,
    customer_name varchar(255) DEFAULT NULL,
    contact_name  varchar(255) DEFAULT NULL,
    address       varchar(255) DEFAULT NULL,
    city          varchar(255) DEFAULT NULL,
    postal_code   varchar(255) DEFAULT NULL,
    country       varchar(255) DEFAULT NULL
);

CREATE TABLE products
(
    product_id   int NOT NULL,
    product_name varchar(255)  DEFAULT NULL,
    supplier_id  int           DEFAULT NULL,
    category_id  int           DEFAULT NULL,
    unit         varchar(255)  DEFAULT NULL,
    price        decimal(5, 2) DEFAULT NULL
);

CREATE TABLE employees
(
    employee_id int NOT NULL,
    last_name   varchar(255) DEFAULT NULL,
    first_name  varchar(255) DEFAULT NULL,
    birth_date  date         DEFAULT NULL,
    photo       varchar(255) DEFAULT NULL,
    notes       text
);

CREATE TABLE orders
(
    order_id    int NOT NULL,
    customer_id int  DEFAULT NULL,
    employee_id int  DEFAULT NULL,
    order_date  date DEFAULT NULL,
    shipper_id  int  DEFAULT NULL
);


CREATE TABLE order_details
(
    order_detail_id int NOT NULL,
    order_id        int DEFAULT NULL,
    product_id      int DEFAULT NULL,
    quantity        int DEFAULT NULL
);

CREATE TABLE shippers
(
    shipper_id   int NOT NULL,
    shipper_name varchar(255) DEFAULT NULL,
    phone        varchar(255) DEFAULT NULL
);

CREATE TABLE shippers_addresses
(
    shipper_address_id int NOT NULL,
    shipper_id         int NOT NULL,
    address            varchar(255) DEFAULT NULL
);

CREATE TABLE suppliers
(
    supplier_id   int NOT NULL,
    supplier_name varchar(255) DEFAULT NULL,
    contact_name  varchar(255) DEFAULT NULL,
    address       varchar(255) DEFAULT NULL,
    city          varchar(255) DEFAULT NULL,
    postal_code   varchar(255) DEFAULT NULL,
    country       varchar(255) DEFAULT NULL,
    phone         varchar(255) DEFAULT NULL
);

CREATE TABLE insert_test
(
    id   integer generated always as identity primary key,
    name varchar(64) not null UNIQUE,
    is_checked bool,
    num int,
    num_f decimal,
    date timestamp with time zone not null
);

CREATE TABLE update_test
(
    id   integer generated always as identity primary key,
    name varchar(64) not null UNIQUE,
    is_checked bool,
    num int,
    num_f decimal,
    date timestamp with time zone not null
);

CREATE TABLE delete_test
(
    id   integer generated always as identity primary key,
    name varchar(64) not null UNIQUE
)
