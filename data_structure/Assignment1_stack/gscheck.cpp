//The main file to Test the stack class
//This program checks at test file for miss matching barces
//by using a stack calss
# include <iostream>
# include "char_stack.h"
using namespace std;
//Declare swap function
char swap(char m);

int main(){
	char_stack S;
	int line_num=0;
	char x;
	char l;
	char line[250];
	bool error_found = false;
	int charPlace = 0;
	int size=0;

	//This while reads the line as far as no error found
	while ( !error_found  && cin.getline(line,250) ){
	 	charPlace = 0;
	 	line_num++;
	 	size=cin.gcount();
	 	//Checks each character of the line--goes through teh line--
	    for (int i=0; line[i] != 0 && !error_found; i++){
	    	//Check if the charcter is opening barcket--and its kind--
			if ( line[i] =='('||line[i]=='{'||line[i]=='[' ){
		    	S.push( line[i] );// push it on the stack if it is opening
		    }
		    //Cheks if it is closing bracket-- and its kind--
			else if ( line[i]==')'||line[i]=='}'||line[i]==']' ){
				//When stack is empty prints that too many closing barckets
				if ( S.empty() ){
					cout<< "Error on line "<<line_num<<" : too many "<<line[i]<<endl;
					charPlace=i;
					error_found =true;
				  //The stack is not emty use swap function the error is found
				  //Prints out the massage
			   	} else{
			   		l=swap(S.pop());

					if ( l != line[i] ) {
				   		cout<<"Error on line "<< line_num<<" : Read "<<line[i]<<", expected "<<l<<endl;
				   		charPlace=i;
		              	error_found = true;
				   	}
			   	}
			}

		}
	}
    //In case of error not found and the closing appropriate
    //bracket does not exist prints out too many one of opening barckets
    //there are extera opening barcket in the text file
	if (!error_found) {
		if(!S.empty()) {
         	x = S.pop();
	    	cout<<"Error on line "<<line_num<<" : too many "<< x <<"\n";

	    	for(int i=0; i<=size;i++){
	    		cout<<line[i];
	    	}
	    	cout<<endl;
	    }

		//there where no error
		else cout <<"No Errors Found\n";
	}

    // When error found
    // These 3 'for's print out the line from
    // beginig up to the error and pring out the
    // spaces as same as the size of the line up to
    // the error and print the rest of the line after the error
   if(error_found){
	for(int k=0;k<=charPlace;k++){
		cout<<line[k];
	}
    cout<< endl;
   for(int m=0;m<=charPlace;m++){
   	 if(line[m]=='\t'){
   		cout<<'\t';
   		} else cout<<' ';
    }
   for(int j=charPlace+1;j<=size;j++){
   	  cout<<line[j];
    }
     cout<<endl;
   }

	return 0;

}

//The swap function which checks if the
//character is opening bracket swap it with the
//equal closing one
char swap (char m ){
	if(m=='(')
       return ')';
    else if (m=='{')
       return '}';
    else if (m=='[')
       return ']';
}
