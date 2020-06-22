<p align="center"><img src="https://sp-cdn.mytaste.org/images/logo-full.svg?v=197" height="50px"></p>

## Backend work sample distributed workers

##### Delivery
Work in your own git and send us a link to your repo.

##### Restrictions
You should use vanilla PHP without any framework. 

##### The assignment
In this exercise, you’ll write a distributed worker using a database table. The worker requests each URL inside the table and stores the resulting response code.
Make sure you can run several workers in parallel. Each URL may only be requested once..

Please share the database table and data in your repository.

<table>
    Example table
    <tr>
        <td>id</td>
        <td>url</td>
        <td>status</td>
        <td>http_code</td>
    </tr>
    <tr>
        <td>1</td>
        <td>http://google.com</td>
        <td>DONE</td>
        <td>200</td>
    </tr>
     <tr>
        <td>1</td>
        <td>http://www.reddit.com</td>
        <td>NEW</td>
        <td>null</td>
    </tr>
</table>

<table>
    Column definitions:
    
    <tr>
        <td>Column</td>
        <td>Description</td>
    </tr>
    <tr>
        <td>id</td>
        <td>Stores an incrementing identifier for the job</td>
    </tr>
    <tr>
        <td>url</td>
        <td>Stores a common URL</td>
    </tr>
    <tr>
        <td>status</td>
        <td>Contains one of the values “NEW”, “PROCESSING”, “DONE” or “ERROR”.</td>
    </tr>
    <tr>
        <td>http_code</td>
        <td>Stores the resulting HTTP­code from the request.</td>
    </tr>
</table>

***

##### Definition of workflow:
* Get next available job
* Call the URL for the job
* Store the returned status