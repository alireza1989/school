///////////////////////////////////////////////////////////////////////////////
//						Project #1 - go project
//
///////////////////////////////////////////////////////////////////////////////


package main

import (
	"fmt"
	"io/ioutil"
	"os"
	"unicode"
)

/////////////////////////////////////////////
//				Main function              //
/////////////////////////////////////////////

func main() {

	// This function raeds the json file and returns all tokens as a big string
	 bigstrings := readJson ()

	// This function reads the tokens from big string and takes the appropriate actions defined in tokenizer
	tokenizer(bigstrings)

}

////////////////////////////////////////////////////////////
//														  //
//				   Read Json file             			  //
// This functin reads the file name from command and puts //
//             everything in a big String 				  //
////////////////////////////////////////////////////////////

func readJson () string {

	// reading the argument from command
	fileName := os.Args

	// raedin the josn file into a slice of byte
	allBytes, err := ioutil.ReadFile(fileName[1])

	// Check if there were any error
	if err != nil {
		panic("There was an error reading from file or file does not exists")
	}

	// Type cast the all byte to bi string
	allStrings := string(allBytes)
	return allStrings

}

/////////////////////////////////////////////
//				Tokenizer function         //
/////////////////////////////////////////////

func tokenizer(allStrings string){

	//flags used all over the program
	var number bool
	var str bool
	var startIndex int
	var endIndex int
	var kind string
	var bSlash bool

	// Reading the tokens one by one from string
	for i := 0; i < len(allStrings); i++ {

		token := rune(allStrings[i])
		if bSlash {
			endIndex++
			bSlash = false
			continue
		}

		// Strings, consider all tokens within double quotation as string
		if token == '\\' {
			bSlash = true
		}
		if token == '"' && str != true {

			str = true
			startIndex = i
			endIndex = i

		} else if str == true && !(token == '"') {

			endIndex++

		} else if (token == '"') && str == true && bSlash == false {

			endIndex++
			stringSlice := allStrings[startIndex : endIndex+1]
			kind = "STRING"
			display(stringSlice, kind)
			str = false
		}

		if str != true {

			// True and False

			if i < (len(allStrings) - 4) {
				if token == 't' && allStrings[i:i+4] == "true" {

					tr := allStrings[i : i+4]
					kind = "TRUE"
					display(tr, kind)
					i = i + 3
					continue
				}
			}
			if i < (len(allStrings) - 5) {
				if token == 'f' && allStrings[i:i+5] == "false" {

					tr := allStrings[i : i+5]
					kind = "FALSE"
					display(tr, kind)
					i = i + 4
					continue
				}
			}

			// numbers: Handle numbers outside teh string

			if token == '-' && number == false {
				number = true
				startIndex = i
				endIndex = i
			} else if (unicode.IsDigit(token) || isHex(token)) && number == false {
				number = true
				startIndex = i
				endIndex = i
			} else if (unicode.IsDigit(token) || isHex(token) || token == '+' || token == '-' || token == 'e' || token == 'E' || token == '.') && number == true {
				endIndex++
			} else if !(unicode.IsDigit(token) || isHex(token)) && number == true {
				kind = "NUMBER"
				numberSlice := allStrings[startIndex : endIndex+1]

				display(numberSlice, kind)
				number = false
			}

			// Fixed size token part

			if token == '{' {
				kind = "OPEN_BRACE"
				display(string(token), kind)
			} else if token == '}' {
				kind = "CLOSE_BRACE"
				display(string(token), kind)
			} else if token == ',' {
				kind = "COMMA"
				display(string(token), kind)
			} else if token == ':' {
				kind = "COLON"
				display(string(token), kind)
			} else if token == '[' {
				kind = "OPEN_BRACKET"
				display(string(token), kind)
			} else if token == ']' {
				kind = "CLOSE_BRACKET"
				display(string(token), kind)
			}
		}
	}

}

// check the hex digit

func isHex(token rune) bool {

	if token == 'a' || token == 'b' || token == 'c' || token == 'd' || token == 'e' || token == 'f' || token == 'A' || token == 'B' || token == 'C' || token == 'D' || token == 'E' || token == 'F' {
		return true
	} else {
		return false
	}
}

// DISPLAY FUNCTION FROM PROJECT INSTRUCTION

func display(token, kind string) {
	fmt.Printf("%-15s %s\n", token, kind)
}
