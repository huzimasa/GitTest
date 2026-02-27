/*--------------------------------------------------------------------------------------------------------------------------------------------------------- 
*ここから問6 int型の初期値を受け取るコンストラクタとメソッド
----------------------------------------------------------------------------------------------------------------------------------------------------------*/

public class Calculation{
	//int型のフィールド変数
	int value;
	
	//int型 の初期値を受け取り value に初期値を保持するコンストラクタ
	Calculation(int number){
		System.out.println("初期値は" + number);
		System.out.println();
		this.value = number;
	}
	
	//【メソッド１】 value の値と引数 add を足し算 valueに代入
	public void add(int addNumber){
		System.out.println("足し算の引数は" + addNumber);
		this.value += addNumber;
	}
	
	//【メソッド2】 value の値と引数 sub を引き算 valueに代入
	public void sub(int subNumber){
		System.out.println("引き算の引数は" + subNumber);
		this.value -= subNumber;
	}
	
	//【メソッド3】 value の値と引数 mul を掛け算 valueに代入
	public void mul(int mulNumber){
		System.out.println("掛け算の引数は" + mulNumber);
		this.value *= mulNumber;
	}
	
	//【メソッド4】value の値と引数 div を割り算 valueに代入
	public void div(int divNumber){
		System.out.println("割り算の引数は" + divNumber);
		this.value /= divNumber;
	}
	
	//【メソッド5】計算結果を出力する引数なしのメソッド
	public void print(){
		System.out.println("現在の計算結果は：" + this.value);
		//見やすくするため空行追加
		System.out.println();
	}
}
