// IntStream をインポート
import java.util.stream.IntStream;

public class Test12{
	/* IntStream.rangeClised で 1 から 100 までの整数 ストリーム生成
	*.reduce でストリーム内の1 から 100まで足す
	* 0 は初期値
	* Integer::sum で2つの整数同士を加算
	*合計を出力
	*/
	public static void main(String[] args){
		System.out.println("1～100の和は：" + IntStream.rangeClosed(1,100).reduce(0, Integer::sum));
	}
}
