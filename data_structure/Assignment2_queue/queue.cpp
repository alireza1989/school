//+++++++++++++++++++++++++++++++++++++++++++++++++++
//					    QUEUE CLASS
//+++++++++++++++++++++++++++++++++++++++++++++++++++

# include <cstring>
# include <iostream>
# include "queue.h"

using namespace std;
//+++++ QUEUE CONSTRUCTOR +++++
queue::queue(){
	front_p = NULL;
	back_p = NULL;
	current_size=0;
}

//++++++++++++++++++++++++++++

//+++++ ENQUEUE +++++
void queue::enqueue(int item){
	if (current_size == 0){
	 	front_p = new node( item , NULL);
	 	back_p = front_p;
	 	current_size ++;
	}
	else {
		node *temp = back_p;
		back_p = new node(item , NULL);
		temp->next = back_p;
		current_size ++;
	}
}

//++++++++++++++++++++++++++++

//+++++ DEQUEUE +++++
int queue::dequeue(){
	if (current_size != 0){
		node *temp = front_p;
		front_p = front_p->next;
		int i = temp->data;
		delete temp;
		current_size --;
		return i;
	}
	else return 0;

}

//++++++++++++++++++++++++++++

//+++++ FRONT +++++
int queue::front(){
	return front_p -> data;
}

//++++++++++++++++++++++++++++

//+++++ EMPTY +++++
bool queue::empty(){
	if(current_size == 0)
		return true;
	else return false;
}

//++++++++++++++++++++++++++++

//+++++ SIZE +++++
int queue::size(){
	return current_size;
}

//++++++++++++++++++++++++++++

//+++++ REMOVE +++++
int queue::remove(int item){
	int counter = 0;

 	node *current_node = front_p;
	node *prev = NULL;

	if(current_node->next !=NULL){
		while(current_node ->next != NULL){
			if(current_node->data == item ){
				node *temp = current_node;
				if(prev == NULL )
					front_p = current_node->next;
				else
					prev->next = current_node->next;
				counter++;
				delete temp;
				current_size --;

			}
			else
				prev = current_node;
			current_node = current_node->next;
	}
	}
	// In case of the last node
	if(current_node->next ==NULL) {
		cout << "s" << current_node << endl;
		back_p = prev;
		if(current_node->data == item ){
			if(prev)
				prev -> next = NULL;
			delete current_node;
			counter++;
			current_size --;
		}

	}
	if(!prev)
		front_p = NULL;

	return counter;
	}

//++++++++++++++++++++++++++++
