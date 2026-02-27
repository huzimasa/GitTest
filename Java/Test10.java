//インポート
import java.util.ArrayList;
import java.util.List;

public class Test10{
	public static void main(String[] args){
		//ListにA,B,C,Dを追加
		List<String> alp = new ArrayList<String>();
		alp.add("A");
		alp.add("B");
		alp.add("C");
		alp.add("D");
		
		//何番目かカウントするための配列 int count [0]を宣言
		int[] count = {0};
		
		//alp.stream() でListをStreamに変換
		alp.stream().forEach(element -> {
			//element で、forEachで取り出された "A"、"B"、"C"、"D" を1つずつ出力
			System.out.println("Listの" + count [0] + "番目：\"" + element + "\"");
			//count = [0]、[1]、[2]、[3](0からListの要素数分4回)
			count[0]++;
		});
	}
}
