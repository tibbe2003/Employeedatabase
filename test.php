<?php  
 //index.php  
 $connect = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003");  
          $query = 'SELECT Employees.EmployeeID, Employees.FirstName, Employees.LastName, Employees.Email, Employees.Phone, Employees.BirthDate, Employees.Adress, Employees.City, Jobtitles.Jobtitles, BusinessUnits.BusinessUnit, Employees.Joindate, Employees.Salary 
            FROM employees 
            JOIN Jobtitles ON Employees.JobID=Jobtitles.JobID
            JOIN BusinessUnits ON Employees.UnitID=BusinessUnits.UnitID 
            ORDER BY employeeid DESC'; 
 $result = pg_query($connect, $query);  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>test</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br />            
           <div class="container" style="width:700px;" align="center">  
                <h3 class="text-center">Ajax Jquery Column Sort with PHP & MySql</h3><br />  
                <div class="table-responsive" id="employee_table">  
                     <table class="table table-bordered">  
                          <tr>  
                               <th><a class="column_sort" id="employeeid" data-order="desc" href="#">ID</a></th>  
                               <th><a class="column_sort" id="firstname" data-order="desc" href="#">Firstname</a></th>  
                               <th><a class="column_sort" id="lastname" data-order="desc" href="#">Lastname</a></th> 
                               <th>Email</th>
                               <th>Phone</th>
                               <th><a class="column_sort" id="birthdate" data-order="desc" href="#">Birthdate</a></th>
                               <th>Adress</th>
                               <th>City</th>
                               <th>Jobtitle</th>
                               <th>Businessunit</th>
                               <th><a class="column_sort" id="joindate" data-order="desc" href="#">Joindate</a></th>
                               <th>Salary</th>  
                          </tr>  
                          <?php  
                          while($row = pg_fetch_array($result))  
                          {  
                          ?>  
                          <tr>  
                               <td><?php echo $row["employeeid"]; ?></td>  
                               <td><?php echo $row["firstname"]; ?></td>  
                               <td><?php echo $row["lastname"]; ?></td> 
                               <td><?php echo $row["email"]; ?></td>
                               <td><?php echo $row["phone"]; ?></td>
                               <td><?php echo $row["birthdate"]; ?></td>
                               <td><?php echo $row["adress"]; ?></td> 
                               <td><?php echo $row["city"]; ?></td> 
                               <td><?php echo $row["jobtitles"]; ?></td>
                               <td><?php echo $row["businessunit"]; ?></td>
                               <td><?php echo $row["joindate"]; ?></td> 
                               <td><?php echo $row["salary"]; ?></td> 
                          </tr>  
                          <?php  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
           <br />  
      </body>  
 </html>  
 <script>  
 $(document).ready(function(){  
      $(document).on('click', '.column_sort', function(){  
           var column_name = $(this).attr("id");  
           var order = $(this).data("order");  
           var arrow = '';  
           //glyphicon glyphicon-arrow-up  
           //glyphicon glyphicon-arrow-down  
           if(order == 'desc')  
           {  
                arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-down"></span>';  
           }  
           else  
           {  
                arrow = '&nbsp;<span class="glyphicon glyphicon-arrow-up"></span>';  
           }  
           $.ajax({  
                url:"sort.php",  
                method:"POST",  
                data:{column_name:column_name, order:order},  
                success:function(data)  
                {  
                     $('#employee_table').html(data);  
                     $('#'+column_name+'').append(arrow);  
                }  
           })  
      });  
 });  
 </script>  