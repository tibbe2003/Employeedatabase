DROP TABLE IF EXISTS Employees, Jobtitles, BusinessUnits, Customers, Deployment, notes, companyinfo, users CASCADE;

CREATE TABLE Jobtitles (
JobID SERIAL,
Jobtitles VARCHAR(255),
	PRIMARY KEY(JobID)
);

CREATE TABLE BusinessUnits (
UnitID SERIAL,
BusinessUnit VARCHAR(255),
	PRIMARY KEY(UnitID)
);

CREATE TABLE Employees (
EmployeeID SERIAL,
FirstName VARCHAR(255) NOT NULL,
LastName VARCHAR(255) NOT NULL,
Email VARCHAR(255),
Phone VARCHAR(20),
BirthDate DATE,
Adress VARCHAR(255),
City VARCHAR(255),
JobID INT,
UnitID INT,
Joindate DATE,
Salary  VARCHAR,
	PRIMARY KEY(EmployeeID),
	CONSTRAINT  fk_jobtitle
		FOREIGN KEY (JobID)
		REFERENCES Jobtitles(JobID),
	CONSTRAINT fk_BusinessUnit
		FOREIGN KEY (UnitID)
		REFERENCES BusinessUnits(UnitID)
);

CREATE TABLE Customers (
CustomerID SERIAL,
CustomerName VARCHAR(225) NOT NULL,
ContactName VARCHAR(255),
Email VARCHAR(255),
Website VARCHAR(255),
Adress VARCHAR (255),
AssignedTo VARCHAR(255),
	PRIMARY KEY(CustomerID)
);

CREATE TABLE Deployment (
CustomerID INT,
EmployeeID INT,
	PRIMARY KEY(EmployeeID,  CustomerID),
	CONSTRAINT Customer
		FOREIGN KEY(CustomerID)
		REFERENCES Customers(CustomerID),
	CONSTRAINT Employee
		FOREIGN KEY(EmployeeID)
		REFERENCES Employees(EmployeeID)
);

CREATE TABLE notes (
NoteID SERIAL,
NoteName VARCHAR(255),
Note TEXT,
	PRIMARY KEY(NoteID)
);

CREATE TABLE companyinfo (
companyname VARCHAR(255),
street VARCHAR(255),
postalcode VARCHAR(255),
city VARCHAR(255)
);

CREATE TABLE users (
    usersid INT NOT NULL,
    usersname VARCHAR(128) NOT NULL,
    usersemail VARCHAR(50) NOT NULL UNIQUE,
    usersuid VARCHAR(128) NOT NULL,
    userspwd VARCHAR(255) NOT NULL,
    	PRIMARY KEY (usersid)
);