public class Test4{
	public static void main(String[] args){
		int a = 5;
		int b = 6;
		int result = 1;
		//要素番号の確認のため出力
		System.out.println(a + "の" + b + "乗は");
		// 6回で処理終了
		for(int i = 1; i <= b; i++){
			result = result * a;
		}
		//5 の 6乗を出力(計算結果は15625)
		System.out.println(result);
	}
}
