#include "Seq.h"
#include <iostream>
#include <string>
using namespace std;

int main() {
   cout << "!!!Hello World!!!" << endl; // prints !!!Hello World!!!
   Seq<int> s1;
   Seq<string> s2;
   Seq<float> s3;
  cout << "Test the template class for Type int : " << endl;     
  s1.add(5);
  s1.add(3);
  s1.add(6);
  cout << "First add 3 numbers" << endl ;
  s1.display();
  cout << "insert an int in pos 1 " << endl ;
  s1.insertAt(4,1);
  s1.display();
  cout << "remove 3" << endl;
  s1.remove(3);
  s1.display();

  cout << "Test the template class for Type string : " << endl;  
  s2.add("Hello Every One!");
  s2.add("Go home!");
  s2.add("Welcome!");
  cout << "First add 3 strings" << endl ;  
  s2.display();
  cout << "insert an string at pos 2" << endl ;
  s2.insertAt("Bad Boy!",2);
  s2.display();
  cout << "remove string \"Go home!\" "  << endl ;
  s2.remove("Go home!");
  s2.display();

  cout << "Test the template class for Type float numbers : " << endl;  
  s3.add(1.0001);
  s3.add(2.000256);
  s3.add(300.333);
  cout << "First add 3 float numbers" << endl ;  
  s3.display();
  cout << "insert an float number at pos 3" << endl ;
  s3.insertAt(500.336,3);
  s3.display();
  cout << "remove float number '1.0001' "  << endl ;
  s3.remove(1.0001);
  s3.display();

   return 0;
}
