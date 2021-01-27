INSERT INTO employees (FirstName, LastName, Email, Phone, BirthDate, Adress, City, JobID, UnitID, Joindate, Salary)
VALUES ('Thijmen','Brand','Thijmen@ik.nu','0640018231','2003-11-22','Dennehoutstraat 21','Helmond','1','1','2020-12-07','$100.000')

INSERT INTO employees (FirstName, LastName, Email, Phone, BirthDate, Adress, City, JobID, UnitID, Joindate, Salary)
VALUES ('Aukje','Brand','Aukje@ik.nu','0641234567','2002-08-05','Dennehoutstraat 21','Helmond','2','1','2020-01-07','$50.000')


INSERT INTO employees (FirstName, LastName, Email, Phone, BirthDate, Adress, City, JobID, UnitID, Joindate, Salary)
VALUES ('Inge','Pebesma','Inge.pebesma@ict.eu','0641234568','1977-04-04','Dennehoutstraat 21','Helmond','3','2','2006-01-07','$7.000')

INSERT INTO Jobtitles (JobID,Jobtitles)
VALUES ('1','CEO')

INSERT INTO Jobtitles (JobID,Jobtitles)
VALUES ('2','Operations manager')

INSERT INTO Jobtitles (JobID,Jobtitles)
VALUES ('3','Business unit manager')

INSERT INTO Jobtitles (JobID,Jobtitles)
VALUES ('4','Professional')

INSERT INTO BusinessUnits (UnitID, BusinessUnit)
VALUES ('1','ORANET NV')

INSERT INTO BusinessUnits (UnitID, BusinessUnit)
VALUES ('2','Machine&systems')

INSERT INTO BusinessUnits (UnitID, BusinessUnit)
VALUES ('3','OrangeNXT')

INSERT INTO BusinessUnits (UnitID, BusinessUnit)
VALUES ('4','Water&Infra')

INSERT INTO Customers (CustomerName, ContactName, Email, Website, Adress)
VALUES ('ICT group NV','Inge Pebesma','Inge.pebesma@ict.eu','https://ict.eu','Rechterstraat 3')

INSERT INTO Deployment (CustomerID, EmployeeID)
VALUES ('1','1')

