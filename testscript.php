<?php
 select employees.employeeid, jobtitles.jobtitles, jobxrole.accesslevel
 from employees, jobxrole, jobtitles
 where employeeid = 2 and jobxrole.jobid = 1 and jobtitles.jobid = 1


//jobid ophalen voor volgende query
$jobidqry = pg_query_params($conn,"SELECT jobid FROM employees WHERE employeeid = $1",array($id));
$jobiddata = pg_fetch_array($jobidqry);
$jobid = $jobiddata['jobid'];

 $roleqry = pg_query_params($conn,"SELECT employees.employeeid, jobxrole.accesslevel
                        FROM employees, jobxrole, jobtitles
                        WHERE employeeid = $1 AND jobxrole.jobid = $2 AND jobtitles.jobid = $2",array($id,$jobid));
$roledata = pg_fetch_array($roleqry);
$role = $roledata['accesslevel'];