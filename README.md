# SOLID Principles - simple and easy explanation

SOLID Principles is a coding standard that all developers should have a clear 
concept for developing software in a proper way to avoid a bad design. 
It was promoted by Robert C Martin and is used across the object-oriented design spectrum.
When applied properly it makes your code more extendable, logical and easier to read.

When the developer builds a software follow the bad design, the code can become 
inflexible and more brittle, small changes in the software can result in bugs. 
For these reasons, we should follow SOLID Principles.


It takes some time to understand, but coding to follow the principles that can greatly 
improve code quality and even understanding of the most well-designed applications on the web.

Understanding SOLID principles you have to know the use of the interface clearly.
If your concept is not clear about interface then you can read this [doc](https://medium.com/@NahidulHasan/understanding-use-of-interface-and-abstract-class-9a82f5f15837).

I'm going to try to explain SOLID Principles in simplest way so that it's easy 
for beginners to understand. Let's go through each principle one by one:


### Single Responsibility Principle :

>A class should have one, and only one, reason to change.

One class should only serve one purpose, this does not imply that each class 
should have only have one method but they should all relate directly to the 
responsibility of the class.All the methods and properties should all work towards 
the same goal. When a class serves for multiple purposes/ responsibility then it should 
be made into a new class.


### Open-closed Principle :

>Entities should be open for extension, but closed for modification.

Software entities (classes, modules, functions, etc.) be extendable without 
actually changing the contents of the class you're extending. If we could follow this 
principle strongly enough, it is possible to then modify the behavior of our code without
ever touching a piece of original code.

### Liskov Substitution Principle :

>Derived classes must be substitutable for their base classes.
 
If you create a class with a dependency of a given type, you should be able to 
provide an object of that type or any of its subclasses without introducing unexpected 
results and without the dependent class knowing the actual type of the provided dependency.


### Interface Segregation Principle :

>A Client should not be forced to implement an interface that it doesn't use.

This rule means that when one class depends upon another, the number 
of members in the interface that is visible to the dependent class should be minimised.


### Dependency Inversion Principle :

> High-level modules should not depend on low-level modules. Both should depend on abstractions.

> Abstractions should not depend on details. Details should depend on abstractions.

Or simply : Depend on Abstractions not on concretions


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

There's a common misunderstanding that dependency inversion is simply another way to say 
dependency injection. However, the two are not the same.In the above code Inspite of 
Injecting MySQLConnection class in PasswordReminder class but it is depends on 
MySQLConnection.

High-level module PasswordReminder should not depend on low-level module MySQLConnection.

If we want to change connection from MySQLConnection to MongoDBConnection, we have to change hard coded constructor injection in PasswordReminder class.

PasswordReminder class should depend upon on Abstractions not on concretions. But How can we do it ? Please see the following example :

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

In the above code we want to change connection from MySQLConnection to MongoDBConnection, 
we no need to change constructor injection in PasswordReminder class. 
Because here PasswordReminder class depends upon on Abstractions not on concretions.