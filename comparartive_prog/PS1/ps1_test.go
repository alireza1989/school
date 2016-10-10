////////////////////////////////////////////////////////////////////////////////////
//																																    //
//                       Functions and test scripts in ps1.go and ps1_test.go
//
/////////////////////////////////////////////////////////////////////////////////////

package ps1

import (
	"errors"
	"testing"
)

///////////////////////////////////////////////
//											 //
// 		  PROBLEM #1 - TEST SCRIPT	         //
//											 //
///////////////////////////////////////////////

// MAke an input/output set
type inout1 struct {
	in, out int
}

var testSet1 = []inout1{
	inout1{4, 2},
	inout1{10000, 1229},
	inout1{-6, 0},
	inout1{1000000, 78498},
}

// Test function
func TestCountPrime(t *testing.T) {
	for _, dt := range testSet1 {
		result := countPrime(dt.in)
		if result != dt.out {
			t.Errorf("CountPrime(%d) = %d, want %d", dt.in, result, dt.out)
		}
	}

}

///////////////////////////////////////////////
//											 //
// 		  PROBLEM #2 - TEST SCRIPT	         //
//											 //
///////////////////////////////////////////////

// Make an input/output set
type inout2 struct {
	fileName string
	fileMap  map[string]int
}

var map1 map[string]int = map[string]int{"The": 1, "the": 1, "big": 3, "dog": 1, "ate": 1, "apple": 1}
var map2 map[string]int = map[string]int{"is": 2, "to": 1, "see": 1, "Hi": 1, "this": 1, "Test": 1, "test": 1, "TestHi": 1, "There": 1, "nothing": 1, "there": 2, "a": 1}

var testSet2 = []inout2{
	inout2{"sample.txt", map1},
	inout2{"sample1.txt", map2},
}

// Test function
func TestCountStrings(t *testing.T) {
	for _, dt := range testSet2 {
		result := countStrings(dt.fileName)
		if !eq(result, dt.fileMap) {
			t.Errorf("\n\n The map in the output of CountString[%s] is: \n\n %v \n\n But it should be: \n\n %v \n\n", dt.fileName, result, dt.fileMap)
		}
	}

}

// Compares two maps

func eq(a, b map[string]int) bool {
	if len(a) != len(b) {
		return false
	}

	for k, _ := range a {
		if a[k] != b[k] {
			return false
		}
	}
	return true
}

///////////////////////////////////////////////
//											 //
// 		 PROBLEM #3 and 4 - TEST SCRIPT	     //
//											 //
///////////////////////////////////////////////

// Testing "validTime24"
func Test_validTime24(t *testing.T) {

	time1 := Time24{hour: 3, minute: 20, second: 45}
	time2 := Time24{hour: 3, minute: 65, second: 45}

	if !validTime24(time1) {
		t.Errorf("The expected result was True but it returned %v \n", validTime24(time1))
	}
	if validTime24(time2) {
		t.Errorf("The expected result was False but it returned %v \n", validTime24(time2))
	}
}

// Testing "equalsTime24"
func Test_equalsTime24(t *testing.T) {

	time1 := Time24{hour: 3, minute: 20, second: 45}
	time2 := Time24{hour: 3, minute: 20, second: 45}
	time3 := Time24{hour: 3, minute: 20, second: 45}
	time4 := Time24{hour: 3, minute: 21, second: 45}

	if !equalsTime24(time1, time2) {
		t.Errorf("The expected result was True but it returned %v \n", equalsTime24(time1, time2))
	}
	if equalsTime24(time3, time4) {
		t.Errorf("The expected result was False but it returned %v \n", equalsTime24(time3, time4))
	}
}

// Testing "lessThanTime24(a, b)"
func Test_lessThanTime24(t *testing.T) {

	time1 := Time24{hour: 2, minute: 20, second: 45}
	time2 := Time24{hour: 3, minute: 20, second: 45}
	time3 := Time24{hour: 4, minute: 20, second: 45}
	time4 := Time24{hour: 3, minute: 21, second: 45}

	if !lessThanTime24(time1, time2) {
		t.Errorf("The expected result was True but it returned %v \n", equalsTime24(time1, time2))
	}
	if lessThanTime24(time3, time4) {
		t.Errorf("The expected result was False but it returned %v \n", equalsTime24(time3, time4))
	}

	time5 := Time24{hour: 3, minute: 17, second: 45}
	time6 := Time24{hour: 3, minute: 20, second: 45}
	time7 := Time24{hour: 4, minute: 24, second: 45}
	time8 := Time24{hour: 3, minute: 21, second: 45}

	if !lessThanTime24(time5, time6) {
		t.Errorf("The expected result was True but it returned %v \n", equalsTime24(time5, time6))
	}
	if lessThanTime24(time7, time8) {
		t.Errorf("The expected result was False but it returned %v \n", equalsTime24(time7, time8))
	}
}

// Testing "minTime24"
func Test_minTime24(t *testing.T) {

	times1 := []Time24{
		Time24{20, 33, 18},
		Time24{22, 15, 10},
		Time24{10, 18, 17},
		Time24{10, 18, 10},
		Time24{6, 24, 12},
		Time24{6, 20, 55},
		Time24{11, 26, 40},
		Time24{23, 10, 55},
	}

	output1, err1 := minTime24(times1)
	result1 := Time24{6, 20, 55}
	expectederr1 := errors.New("nil")

	times2 := []Time24{}
	output2, err2 := minTime24(times2)
	result2 := Time24{0, 0, 0}
	expectederr2 := errors.New("Times slice is empty")

	if output1 != result1 && err1 != expectederr1 {
		t.Errorf("The expected result was min:\"06:20:55\" and error: \"nil\" but it returned %v, %v \n", output1, err1)
	}
	if output2 != result2 && err2 != expectederr2 {
		t.Errorf("The expected result was min:\"00:00:00\" and error: \"Times slice is empty\" but it returned %v, %v \n", output2, err2)
	}
}

////////////////////////////////////////
//
//	The methhod String does not need a test script since if it does not work
//  it would change the formation and all the test scrips for other functions would fail.
//
///////////////////////////////////////

// Testing AddOneHour Method
func Test_AddOneHour(t *testing.T) {

	t1 := Time24{hour: 20, minute: 39, second: 8}

	result1 := Time24{hour: 21, minute: 39, second: 8}

	t2 := Time24{hour: 24, minute: 39, second: 8}

	result2 := Time24{hour: 0, minute: 39, second: 8}

	t1.AddOneHour()

	if t1 != result1 {

		t.Errorf("The expected result was %v but it returned %v \n", result1, t1)
	}

	t2.AddOneHour()

	if t2 != result2 {

		t.Errorf("The expected result was %v but it returned %v \n", result2, t2)
	}

}

///////////////////////////////////////////////
//											 //
// 		  PROBLEM #5 - TEST SCRIPT	         //
//											 //
///////////////////////////////////////////////

// Testing all bit seq bit sequence generator
func Test_allBitSeqs(t *testing.T) {

	n1 := 2
	var result1 = [][]int{{0, 0}, {0, 1}, {1, 0}, {1, 1}}
	output1 := allBitSeqs(n1)

	if isEqual(output1, result1, n1) {
		t.Errorf("The output bit sequence is: \n %v\n The expected nit sequence is: %v", output1, result1)
	}

	n2 := 0
	var result2 = [][]int{{}}
	output2 := allBitSeqs(n2)

	if isEqual(output1, result2, n2) {
		t.Errorf("The output bit sequence is: \n %v\n The expected nit sequence is: %v", output2, result2)
	}

}

// This function compares two 2D arrays
func isEqual(x, y [][]int, n int) bool {

	if x == nil && y == nil {
		return true
	}

	if x == nil || y == nil {
		return false
	}

	if len(x) != len(y) {
		return false
	}

	for i := range x {

		for j := 0; j < n; j++ {

			if x[i][j] != y[i][j] {
				return false
			}
		}
	}

	return true
}
