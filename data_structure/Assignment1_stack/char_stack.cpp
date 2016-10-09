/* File: char_stack.cpp*/

#include "char_stack.h"// contains the declarations of the variables and functions.

// Fill this in.

// **SOME PART OF TEH CODE IS COPIED FROM THE COURSE WEB PAGE**
char_stack::char_stack(){
  // the default constructor intitializes the private variables.
  capacity = 100;
  A = new char[capacity];
  stack_size = 0;
}

void char_stack::push( char item ){
  A[stack_size] = item;
  stack_size ++;
}

char char_stack::top(){
  return A[stack_size-1];
}

char char_stack::pop(){
  stack_size = stack_size - 1 ;
  return A[ stack_size ];
}


bool char_stack::empty(){
  return stack_size == 0 ;
}
int char_stack::size()
{
	return stack_size;
}
