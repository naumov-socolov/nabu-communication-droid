## About Communication System

It is demo project.

Communication Droid is capable to communicate with completely different Solar Systems with its own specifics. 
Its main goal is to message distant galaxies and purchase plasma guns. It expects clear response if facilities would be delivered. 
Every Solar System has its own private API, with specific response codes and request sequences.
Some of them could give final response with a single request, other set request on a hold, so request sequence is necessary in this case. 

Technically all Solar System integrations are arranged into unified and flexible system that allows to implement specific request handling for each Solar System case.
To keep system maintainable and stable it is made highly SOLID, classes are loosely-coupled, interchangeable and are easy to test. 

As a result Driod will convert all response messages into unified structure no matter how response was received. All request are logged as well. 

Being a demo project, some functionality has been simplified, excluding architecture, it is made to perform.  
   