# SOLID Principles - simple and easy explanation

SOLID Principles is a coding standard that all developers should have a clear 
concept for developing software in a proper way to avoid a bad design. 
It was promoted by Robert C Martin and is used across the object-oriented design spectrum.
When applied properly it makes your code more extendable, logical and easier to read.

When the developer builds a software follow the bad design, the code can become 
inflexible and more brittle, small changes in the software can result in bugs. 
For these reasons, we should follow SOLID Principles.

It takes some time to understand, but if you write code following the principles 
it will improve code quality and will help to understand the most well-designed software.

To understand SOLID principles, you have to know the use of the interface clearly.
If your concept is not clear about interface then you can read this [doc](https://medium.com/@NahidulHasan/understanding-use-of-interface-and-abstract-class-9a82f5f15837).

I'm going to try to explain SOLID Principles in simplest way so that it's easy 
for beginners to understand. Let's go through each principle one by one:


## Single Responsibility Principle :

>A class should have one, and only one, reason to change.

One class should only serve one purpose, this does not imply that each class should have only one method but they should all relate directly to the responsibility of the class. All the methods and properties should all work towards the same goal. When a class serves multiple purposes or responsibility then it should be made into a new class.

Please look at the following code :

```php
namespace Demo;
use DB;
class OrdersReport
{
    public function getOrdersInfo($startDate, $endDate)
    {
        $orders = $this->queryDBForOrders($startDate, $endDate);
        return $this->format($orders);
    }
    protected function queryDBForOrders($startDate, $endDate)
    {   // If we would update our persistence layer in the future,
        // we would have to do changes here too. <=> reason to change!
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
    protected function format($orders)
    {   // If we changed the way we want to format the output,
        // we would have to make changes here. <=> reason to change!
        return '<h1>Orders: ' . $orders . '</h1>';
    }
}
```

Above class violates single responsibility principle. Why should this class retrieve data from database? It is related to the 
persistence layer. The persistence layer deals with persisting (storing and retrieving) data from a data store (such as a database, for example).So it is not the responsibility of this class.

Next method format is also not the responsibility of this class. Because we may need different format data such as XML, JSON, HTML etc.

So finally the refactored code will be described as below :

```php
namespace Report;
use Report\Repositories\OrdersRepository;
class OrdersReport
{
    protected $repo;
    protected $formatter;
    public function __construct(OrdersRepository $repo, OrdersOutPutInterface $formatter)
    {
        $this->repo = $repo;
        $this->formatter = $formatter;
    }
    public function getOrdersInfo($startDate, $endDate)
    {
        $orders = $this->repo->getOrdersWithDate($startDate, $endDate);
        return $this->formatter->output($orders);
    }
}

namespace Report;
interface OrdersOutPutInterface
{
	public function output($orders);
}
namespace Report;
class HtmlOutput implements OrdersOutPutInterface
{
    public function output($orders)
    {
        return '<h1>Orders: ' . $orders . '</h1>';
    }
}

namespace Report\Repositories;
use DB;
class OrdersRepository
{
    public function getOrdersWithDate($startDate, $endDate)
    {
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
}
```

## Open-closed Principle :

>Entities should be open for extension, but closed for modification.

Software entities (classes, modules, functions, etc.) be extendable without actually changing the contents of the class you're extending. If we could follow this principle strongly enough, it is possible to then modify the behavior of our code without ever touching a piece of original code.

Please look at the following code :

```php
class Rectangle
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
}

class Circle
{
    public $radius;
    public function __construct($radius)
    {
        $this->radius = $radius;
    }
}

class CostManager
{
    public function calculate($shape)
    {
        $costPerUnit = 1.5;
        if ($shape instanceof Rectangle) {
            $area = $shape->width * $shape->height;
        } else {
            $area = $shape->radius * $shape->radius * pi();
        }
        
        return $costPerUnit * $area;
    }
}
$circle = new Circle(5);
$rect = new Rectangle(8,5);
$obj = new CostManager();
echo $obj->calculate($circle);
```

If we want to calculate the area for Square we have to modify calculate method in
 CostManager class. It breaks the open-closed principle. According to this principle, 
 we can not modify we can extend. So How we can fix this problem, please see the following code :


```php
interface AreaInterface
{
    public  function calculateArea();
}

class Rectangle implements AreaInterface
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
    public  function calculateArea(){
        $area = $this->height *  $this->width;
        return $area;
    }
}
  
class Circle implements  AreaInterface
{
    public  $radius;
    public function __construct($radius)
    {
        $this->radius = $radius;
    }
    public  function calculateArea(){
        $area = $this->radius * $this->radius * pi();
        return $area;
    }
}

class CostManager
{
    public function calculate(AreaInterface $shape)
    {
        $costPerUnit = 1.5;
        $totalCost = $costPerUnit * $shape->calculateArea();
        return $totalCost;
    }
}
$circle = new Circle(5);
$obj = new CostManager();
echo $obj->calculate($circle);
```

Now we can find square's area without modifying CostManager class.


## Liskov Substitution Principle :

The Liskov Substitution principle was introduced by Barbara Liskov in her conference 
keynote "Data abstraction" in 1987.Barbara Liskov and Jeannette Wing formulated 
the principle succinctly in a 1994 paper as follows:

>Let φ(x) be a property provable about objects x of type T. Then φ(y) should be true for objects y of type S where S is a subtype of T.


The human-readable version repeats pretty much everything that Bertrand Meyer 
already has said, but it relies totally on a type-system:


>1. Preconditions cannot be strengthened in a subtype.
>2. Postconditions cannot be weakened in a subtype.
>3. Invariants of the supertype must be preserved in a subtype.

Robert Martin made the definition sound more smoothly and concisely in 1996 :

>Functions that use pointers of references to base classes must be able to use objects of derived classes without knowing it.

Or simply : Subclass/derived class should be substitutable for their base/parent class.

It states that any implementation of an abstraction (interface) should be 
substitutable in any place that the abstraction is accepted. Basically, 
it takes care that while coding using interfaces in our code, 
we not only have a contract of input that the interface receives but also the 
output returned by different Classes implementing that interface; they should be 
of the same type.

A code snippet to show how violates LSP and how we can fix it :

```php
interface LessonRepositoryInterface
{
    /**
     * Fetch all records.
     *
     * @return array
     */
    public function getAll();
}

class FileLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        // return through file system
        return [];
    }
}

class DbLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        /*
            Violates LSP because:
              - the return type is different
              - the consumer of this subclass and FileLessonRepository won't work identically
         */
        // return Lesson::all();
        // to fix this
        return Lesson::all()->toArray();
    }
}
```


## Interface Segregation Principle :

>A Client should not be forced to implement an interface that it doesn't use.

This rule means that we should break our interfaces in many smaller ones, 
so they better satisfy the exact needs of our clients.

Similar to the Single Responsibility Principle, the goal of the Interface Segregation Principle is to minimize the side consequences and repetition by dividing the software into multiple, independent parts.

Let’s see an example :

```php
interface workerInterface
{
    public  function work();
    public  function  sleep();
}

class HumanWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function  sleep()
    {
        var_dump('sleep');
    }

}

class RobotWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function sleep()
    {
        // No need
    }
}
```

In the above code, RobotWorker no needs sleep, but the class has to implement the sleep method because we know that all methods are abstract in the interface. It breaks the Interface segregation law. How we can fix it please see the following code :

```php
interface WorkAbleInterface
{
    public  function work();
}

interface SleepAbleInterface
{
    public  function  sleep();
}

class HumanWorker implements WorkAbleInterface, SleepAbleInterface
{
    public  function work()
    {
        var_dump('works');
    }
    public  function  sleep()
    {
        var_dump('sleep');
    }
}

class RobotWorker implements WorkAbleInterface
{
    public  function work()
    {
        var_dump('works');
    }
}
```


## Dependency Inversion Principle :

> High-level modules should not depend on low-level modules. Both should depend on abstractions.

> Abstractions should not depend on details. Details should depend on abstractions.

Or simply : Depend on Abstractions not on concretions

By applying the Dependency Inversion the modules can be easily changed by other modules just 
changing the dependency module and High-level module will not be affected by any changes to 
the Low-level module.

Please look at the following code :

```php
class MySQLConnection
{
   /**
    * db connection
    */
    public function connect()
    {
      var_dump('MYSQL Connection');
    }
}

class PasswordReminder
{    
    /**
     * @var MySQLConnection
     */
     private $dbConnection;
     
     public function __construct(MySQLConnection $dbConnection) 
    {
      $this->dbConnection = $dbConnection;
    }
}
```

There's a common misunderstanding that dependency inversion is simply another way to say dependency injection. However, the two are not the same.

In the above code In spite of Injecting MySQLConnection class in PasswordReminder class but it depends on MySQLConnection.

High-level module PasswordReminder should not depend on low-level module MySQLConnection.

If we want to change the connection from MySQLConnection to MongoDBConnection, we have to change hard-coded constructor injection in PasswordReminder class.

PasswordReminder class should depend upon on Abstractions, not on concretions. But How can we do it? Please see the following example :

```php
interface ConnectionInterface
{
   public function connect();
}

class DbConnection implements ConnectionInterface
{
   /**
    * db connection
    */
    public function connect()
    {
     var_dump('MYSQL Connection');
    }
}

class PasswordReminder
{
   /**
    * @var DBConnection
    */
    private $dbConnection;
    
    public function __construct(ConnectionInterface $dbConnection)
    {
      $this->dbConnection = $dbConnection;
    }
}
```

In the above code, we want to change the connection from MySQLConnection to MongoDBConnection, we no need to change constructor injection in PasswordReminder class. Because here PasswordReminder class depends upon on Abstractions, not on concretions.

The Publication Better Programming has published this article. if you’d like to read from the Better Programming blog site, please go to this [link](https://medium.com/better-programming/solid-principles-simple-and-easy-explanation-f57d86c47a7f).

Thanks for reading.


### License

Open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)


