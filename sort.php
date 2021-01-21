<?php  
 //sort.php  
 $connect = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003"); 
 $output = '';  
 $order = $_POST["order"];  
 if($order == 'desc')  
 {  
      $order = 'asc';  
 }  
 else  
 {  
      $order = 'desc';  
 }  
           $query = 'SELECT Employees.EmployeeID, Employees.FirstName, Employees.LastName, Employees.Email, Employees.Phone, Employees.BirthDate, Employees.Adress, Employees.City, Jobtitles.Jobtitles, BusinessUnits.BusinessUnit, Employees.Joindate, Employees.Salary 
            FROM employees 
            JOIN Jobtitles ON Employees.JobID=Jobtitles.JobID
            JOIN BusinessUnits ON Employees.UnitID=BusinessUnits.UnitID 
            ORDER BY ' .$_POST["column_name"]." ".$_POST["order"].""; 
 $result = pg_query($connect, $query);  
 $output .= '  
 <table class="table table-bordered">  
      <tr>  
           <th><a class="column_sort" id="employeeid" data-order="'.$order.'" href="#">ID</a></th>  
           <th><a class="column_sort" id="firstname" data-order="'.$order.'" href="#">Firstname</a></th>  
           <th><a class="column_sort" id="lastname" data-order="'.$order.'" href="#">Lastname</a></th> 
           <th>Email</th>
           <th>Phone</th> 
           <th><a class="column_sort" id="birthdate" data-order="'.$order.'" href="#">Birthdate</a></th>
           <th>Adress</th>
           <th>City</th>
           <th>Jobtitle</th>
           <th>Businessunit</th>  
           <th><a class="column_sort" id="joindate" data-order="'.$order.'" href="#">Joindate</a></th> 
           <th>Salary</th> 
      </tr>  
 ';  
 while($row = pg_fetch_array($result))  
 {  
      $output .= '  
      <tr>  
           <td>' . $row["employeeid"] . '</td>  
           <td>' . $row["firstname"] . '</td>  
           <td>' . $row["lastname"] . '</td>
           <td>' . $row["email"] . '</td> 
           <td>' . $row["phone"] . '</td>   
           <td>' . $row["birthdate"] . '</td>  
           <td>' . $row["adress"] . '</td>
           <td>' . $row["city"] . '</td>
           <td>' . $row["jobtitles"] . '</td> 
           <td>' . $row["businessunit"] . '</td>  
           <td>' . $row["joindate"] . '</td>  
           <td>' . $row["salary"] . '</td> 
      </tr>  
      ';  
 }  
 $output .= '</table>';  
 echo $output;  
 ?>  