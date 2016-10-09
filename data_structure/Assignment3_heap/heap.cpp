/***********************************************************
                        heap.cpp
************************************************************/
#include "heap.h"

// Constructors and Destructor

Heap::Heap(void){ // New empty Heap with default capacity.
   h_capacity = 10;
   A = new int[h_capacity];
   h_size = 0;
}

Heap::Heap(int c){ // New empty Heap with capacity c
   // Complete this.
   int h_capacity = c;
   A = new int [h_capacity];
   h_size = 0;

}

Heap::Heap(int * B, int s, int c){ // New Heap with capacity c containing first s elements of B
   // Complete this.  ---  DONE  ---
   int h_capacity = c;
   A = new int [h_capacity];
   h_size = 0;
   for (int i = 0 ; i < s ; i ++){
      insert (B[i]);
      h_size ++;
   }

}
Heap::Heap( const Heap & H ){ // Copy constructor. 
   // Complete this.
   h_size = H.h_size;
   h_capacity= H.h_capacity;
   A = new int[h_capacity];
   for(int i=0 ; i< h_size;i++){
      A[i]=H.A[i];
   }

}

// Destructor
Heap::~Heap(){
   delete[] A;
}


// Operators

Heap Heap::operator+( const Heap & Right_H) const {
   // New Heap with combined contents and size of operands.
   int new_capacity = h_capacity + Right_H.h_capacity ;
   Heap New_H( new_capacity );

   // Complete this.   "*************HERE****************"    ---  DONE  ---
    Heap left = Heap (* this);
    Heap right = Heap (Right_H);

    while (! left.empty() || ! right.empty()){

      if (! left.empty())
            New_H.insert (left.extract_min());
      if (! right.empty())
            New_H.insert (right.extract_min());
    }

  return New_H ;
}

// Modifiers

void Heap::insert(int x){
// Inserts x into the heap.
   A[h_size] = x;
   trickle_up(h_size);
   h_size ++;
}

// Repairs the heap ordering invariant after adding a new element.
// Initial call should be trickle_up(h_size-1).
void Heap::trickle_up(int v){
   // Complete this.   "*************HERE****************"
   int temp = 0;

      if (A[v] < A[(v-1)/2] ){
         int temp = A[v];
         A[v] = A[(v-1)/2];
         A[(v-1)/2] = temp;
         trickle_up (A[(v-1)/2]);
      }



}

// Removes and returns minimum element.
int Heap::extract_min(){
   int temp = A[0];
   A[0] = A[--h_size];
   trickle_down(0);
   return temp;
}

// Repairs the heap ordering invariant after replacing the root.
// Normal initial call should be trickle_down(0).
// trickle_down(i) performs the repair on the subtree rooted a A[i].
// make_heap() call trickle_down(i) for i (h_size/2)-1 down to 0.
void Heap::trickle_down(int i){
   // Complete this.  "*************HERE****************"  ---  DONE  ---
   // Algorithm is from the text book
   int swap = i;
   int temp = 0;
   int left = (2*i+1);

   if (left < h_size - 1 && A[left] < A[i]){
      swap = left;
   }

   int right = (2*i+2);
   if (right < h_size - 1 && A[right] < A[swap]){
      swap = right;
   }

   if ( swap != i){
      temp = A[i];
      A[i] = A[swap];
      A[swap] = temp;
      trickle_down (swap);
   }


}

// Turns A[0] .. A[size-1] into a heap.
void Heap::make_heap(){
   for( int i = (h_size/2)-1; i>=0 ; i-- ) trickle_down(i);
}

// Prints contents of heap to standard output (for testing).
void Heap::display(){
     for(int i = 0; i < h_size ; ++i) cout << A[i] << ", " ;
}
