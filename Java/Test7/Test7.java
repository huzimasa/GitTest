public class Test7{
	public static void main(String[]args){
		
		//Calculation クラスのインスタンスを初期値 10 として作成
		Calculation calculation = new Calculation(10);
		
		//add メソッドに 5の値を渡す
		calculation.add(5);
		//足し算の計算結果が出力される
		calculation.print();
		
		//sub メソッドに 5の値を渡す
		calculation.sub(5);
		//引き算の計算結果が出力される
		calculation.print();
		
		//mulメソッドに 2の値を渡す
		calculation.mul(2);
		//掛け算の計算結果が出力される
		calculation.print();
		
		//divメソッドに 0の値を渡す
		calculation.div(0);
		//割り算の計算結果が出力される
		calculation.print();
	}
}
