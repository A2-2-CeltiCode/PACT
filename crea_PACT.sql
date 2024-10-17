--
-- SCHEMA
--

DROP SCHEMA IF EXISTS pact CASCADE;
CREATE SCHEMA pact;
SET SCHEMA 'pact';

--
-- TABLE ADRESS
--

CREATE TABLE _adress(
    postalCode    INTEGER NOT NULL,
    city          VARCHAR(50) NOT NULL,
    streetName    VARCHAR(50) NOT NULL,
    streetNumber  VARCHAR(5),
    phoneNumber   VARCHAR(15), -- indicatif international différent selon le pays
    CONSTRAINT adress_pk PRIMARY KEY(postalCode,city)
);

CREATE TABLE _option(
    nameOption  VARCHAR(50),
    CONSTRAINT option_pk PRIMARY KEY(nameOption)
);

CREATE TABLE _fixedPrice( -- a modif peut être
    nameFp  VARCHAR(50),
    CONSTRAINT fixedPrice_pk PRIMARY KEY(nameFp)
);

--
-- TABLE PRICE
--

CREATE TABLE _price(
    valPrice  NUMERIC(5,2) UNIQUE
);

--
-- TABLE DURATION
--

CREATE TABLE _duration(
    timeInMinutes   INTEGER UNIQUE
);

--
-- TABLES ACCOUNT,ACCOUNT MEMBER, ACCOUNT PRO, ACCOUNT PRO PUBLIC, ACCOUNT PRO PRIVATE
--

CREATE TABLE _account (
    idAccount   SERIAL,
    login       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL, --CHECK (LENGTH(password) >= 8),
    email       VARCHAR(255) NOT NULL,
    postalCode  INTEGER NOT NULL,
    city        VARCHAR(50) NOT NULL,
    nameOption  VARCHAR(50) NOT NULL,
    nameFp      VARCHAR(50),
    CONSTRAINT account_pk PRIMARY KEY(idAccount),
    CONSTRAINT account_fk_adress FOREIGN KEY (postalCode,city) 
        REFERENCES _adress(postalCode,city),
    CONSTRAINT account_fk_option FOREIGN KEY (nameOption) 
        REFERENCES _option(nameOption),
    CONSTRAINT account_fk_fixedPrice FOREIGN KEY (nameFp)
        REFERENCES _fixedPrice(nameFp)
);

CREATE TABLE _accountMember (
    idAccount   SERIAL,
    firstName   VARCHAR(50) NOT NULL,  
    familyName  VARCHAR(50) NOT NULL,
    CONSTRAINT accountMember_fk_account FOREIGN KEY (idAccount) 
        REFERENCES _account(idAccount)
);

CREATE TABLE _accountPro (
    idAccount         SERIAL,
    corporateName     VARCHAR(50) NOT NULL,  
    companyName       VARCHAR(50) NOT NULL,
    bankRib           VARCHAR(35),
    CONSTRAINT accountPro_pk PRIMARY KEY(idAccount),
    CONSTRAINT accountPro_fk_account FOREIGN KEY (idAccount) 
        REFERENCES _account(idAccount)
);

CREATE TABLE _accountProPublic (
    idAccount     SERIAL,
    CONSTRAINT accountProPublic_fk_accountPro FOREIGN KEY (idAccount) 
        REFERENCES _accountPro(idAccount)
);

CREATE TABLE _accountProPrivate (
    idAccount     SERIAL,
    sirenNumber   CHAR(14) NOT NULL UNIQUE,
    CONSTRAINT accountProPrivate_fk_accountPro FOREIGN KEY (idAccount) 
        REFERENCES _accountPro(idAccount)
);

--
-- TABLE OFFER
--

CREATE TABLE _offer(
    idOffer               SERIAL,
    nameOption            VARCHAR(50),
    nameFp                VARCHAR(50),
    title                 VARCHAR(50) NOT NULL,
    description           VARCHAR(50),
    detailedDescription   VARCHAR(50),
    website               VARCHAR(50),
    CONSTRAINT offer_pk PRIMARY KEY(idOffer),
    CONSTRAINT option_fk_offer FOREIGN KEY (nameOption)
        REFERENCES _option(nameOption),
    CONSTRAINT fixedPrice_fk_offer FOREIGN KEY (nameFp)
        REFERENCES _fixedPrice(nameFp)
);

CREATE TABLE _image(
    idOffer   SERIAL,
    idImage   SERIAL,
    name      VARCHAR(50) NOT NULL,
    CONSTRAINT image_pk PRIMARY KEY(idImage),
    CONSTRAINT image_fk_offer FOREIGN KEY (idOffer)
        REFERENCES _offer(idOffer)
);

--
-- TABLE CATEGORY
--

CREATE TABLE _category(
    idOffer         SERIAL,
    nameCategory    VARCHAR(50) NOT NULL,
    CONSTRAINT category_pk PRIMARY KEY(nameCategory,idOffer),
    CONSTRAINT category_fk_offer FOREIGN KEY (idOffer)
        REFERENCES _offer(idOffer)
    
);

CREATE TABLE _show(
    idOffer       SERIAL,
    nameCategory  VARCHAR(50) NOT NULL,
    timeInMinutes   INTEGER UNIQUE,
    valPrice    NUMERIC(5,2) NOT NULL,
    capacity      INTEGER NOT NULL,
    CONSTRAINT show_pk PRIMARY KEY (idOffer),
    CONSTRAINT show_fk_category FOREIGN KEY (idOffer,nameCategory)
        REFERENCES _category(idOffer,nameCategory),
    CONSTRAINT show_fk_duration FOREIGN KEY (timeInMinutes)
        REFERENCES _duration(timeInMinutes),
    CONSTRAINT show_fk_price FOREIGN KEY (valPrice)
        REFERENCES _price(valPrice)
);

CREATE TABLE _activity(
    idOffer         SERIAL,
    nameCategory    VARCHAR(50) NOT NULL,
    timeInMinutes   INTEGER UNIQUE,
    valPrice    NUMERIC(5,2) NOT NULL,
    ageMin          INTEGER NOT NULL,
    service         VARCHAR(50) NOT NULL, -- prestation
    CONSTRAINT activity_pk PRIMARY KEY (idOffer),
    CONSTRAINT activity_fk_category FOREIGN KEY (idOffer,nameCategory)
        REFERENCES _category(idOffer,nameCategory),
    CONSTRAINT activity_fk_duration FOREIGN KEY (timeInMinutes)
        REFERENCES _duration(timeInMinutes),
    CONSTRAINT activity_fk_price FOREIGN KEY (valPrice)
        REFERENCES _price(valPrice)
);

CREATE TABLE _amusementPark(
    idOffer         SERIAL,
    nameCategory    VARCHAR(50) NOT NULL,
    valPrice    NUMERIC(5,2) NOT NULL,
    parkMap         VARCHAR(50) NOT NULL,
    nbAttractions   INTEGER NOT NULL,
    ageMin          INTEGER NOT NULL,
    CONSTRAINT amusementPark_pk PRIMARY KEY (idOffer),
    CONSTRAINT amusementPark_fk_category FOREIGN KEY (idOffer,nameCategory)
        REFERENCES _category(idOffer,nameCategory),
    CONSTRAINT amusementPark_fk_price FOREIGN KEY (valPrice)
        REFERENCES _price(valPrice)
);

CREATE TABLE _visit(
    idOffer         SERIAL,
    valPrice        NUMERIC(5,2) NOT NULL,
    timeInMinutes   INTEGER UNIQUE,
    nameCategory    VARCHAR(50) NOT NULL,
    isGuided        BOOLEAN NOT NULL,
    CONSTRAINT visit_pk PRIMARY KEY (idOffer),
    CONSTRAINT visit_fk_category FOREIGN KEY (idOffer,nameCategory)
        REFERENCES _category(idOffer,nameCategory),
    CONSTRAINT visit_fk_duration FOREIGN KEY (timeInMinutes)
        REFERENCES _duration(timeInMinutes),
    CONSTRAINT visit_fk_price FOREIGN KEY (valPrice)
        REFERENCES _price(valPrice)
);

CREATE TABLE _restaurant(
    idOffer         SERIAL,
    nameCategory    VARCHAR(50) NOT NULL,
    mapRestaurant   VARCHAR(50) NOT NULL,
    rangeRestaurant VARCHAR(50) NOT NULL,
    CONSTRAINT restaurant_pk PRIMARY KEY (idOffer),
    CONSTRAINT restaurant_fk_category FOREIGN KEY (idOffer,nameCategory)
        REFERENCES _category(idOffer,nameCategory)
);

CREATE TABLE _meal(
    name        VARCHAR(50) NOT NULL,
    valPrice    NUMERIC(5,2) NOT NULL,
    CONSTRAINT meal_pk PRIMARY KEY(name),
    CONSTRAINT meal_fk_price FOREIGN KEY (valPrice)
        REFERENCES _price(valPrice)
);

--
-- TABLE LANGUAGE
--

CREATE TABLE _language(
    nameLanguage    VARCHAR(50) NOT NULL
);

--
-- TABLE TAG
--

CREATE TABLE _tag(
    nameTag    VARCHAR(50),
    CONSTRAINT tag_pk PRIMARY KEY(nameTag)
);

CREATE TABLE _tagOther(
    nameTag    VARCHAR(50),
    CONSTRAINT tagOther_pk PRIMARY KEY(nameTag),
    CONSTRAINT tagOther_fk_tag FOREIGN KEY (nameTag)
        REFERENCES _tag(nameTag)
);

CREATE TABLE _tagRestaurant(
    nameTag    VARCHAR(50),
    CONSTRAINT tagRestaurant_pk PRIMARY KEY(nameTag),
    CONSTRAINT tagRestaurant_fk_tag FOREIGN KEY (nameTag)
        REFERENCES _tag(nameTag)
);

--
-- TABLE ASSOCIATION GUIDEE
--

CREATE TABLE _ownShow (
    idOffer      SERIAL,
    nameTag      VARCHAR(50),
    CONSTRAINT ownShow_fk_show FOREIGN KEY (idOffer) REFERENCES _show(idOffer),
    CONSTRAINT ownShow_fk_tagOther FOREIGN KEY (nameTag) REFERENCES _tagOther(nameTag)
);

CREATE TABLE _ownActivity (
    idOffer      SERIAL,
    nameTag      VARCHAR(50),
    CONSTRAINT ownActivity_fk_activity FOREIGN KEY (idOffer) REFERENCES _activity(idOffer),
    CONSTRAINT ownActivity_fk_tagOther FOREIGN KEY (nameTag) REFERENCES _tagOther(nameTag)
);

CREATE TABLE _ownVisit (
    idOffer      SERIAL,
    nameTag      VARCHAR(50),
    CONSTRAINT ownVisit_fk_visit FOREIGN KEY (idOffer) REFERENCES _visit(idOffer),
    CONSTRAINT ownVisit_fk_tagOther FOREIGN KEY (nameTag) REFERENCES _tagOther(nameTag)
);

CREATE TABLE _ownRestaurant (
    idOffer      SERIAL,
    nameTag      VARCHAR(50),
    CONSTRAINT ownRestaurant_fk_visit FOREIGN KEY (idOffer) REFERENCES _restaurant(idOffer),
    CONSTRAINT ownRestaurant_fk_tagOther FOREIGN KEY (nameTag) REFERENCES _tagOther(nameTag)
);

