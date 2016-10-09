/* File: char_stack.h       

  Header file for a very basic array-based implementation of a stack.
  I am using the stack class which is on the course page
  I have added the stack size function
*/
class char_stack
{
  public:
    char_stack();
    void push( char item );
    char pop();
    char top();
    bool empty();
    int size();

  private:

  int stack_size;
  int capacity;
  char *A ;



};
