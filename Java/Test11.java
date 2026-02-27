import java.util.ArrayList;

public class Test11{
	/*
	* number に対し stream()を使用し、ストリームを生成
	* reduce() で要素同士を足した合計を出す。(1 + 2 + 3 + …100)
	* (sum, i)->sum + i) で2つの引数 sum と i を受け取り足し合わせる
	*/
	public static void main(String[] args){
		//int型の ArrayList numberを宣言。
		ArrayList<Integer> number = new ArrayList<Integer>();
		
		//ArrayList number に1から100を追加
		for(int i = 1; i <= 100; i++){
			number.add(i);
		}
		
		//合計の計算をして total に格納
		int total = number.stream().reduce(0,(sum, i)->sum + i);
		
		//合計の値が出力される
		System.out.println("1～100の和は：" + total);
	}
}

